<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAtlasCodeRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Individuals;
use App\Models\JahadiGroups;
use App\Traits\HttpResponses;
use GuzzleHttp\Client;

class RegisterController extends Controller
{
  use HttpResponses;

  public function register(RegisterRequest $request)
  {
    $request->validated($request->all());
    $registerType = $request->validated()->register_type;
    if ($registerType === 'jahadi_group') {
      return $this->_handleJahadiGroup($request);
    } else if ($registerType === 'individual') {
      return $this->_handleIndividual($request);
    } else if ($registerType === 'group') {
      return $this->_handleGroups($request);
    }
  }

  public function getAtlasCode(GetAtlasCodeRequest $request)
  {
    $jahadiGroup = JahadiGroups::where([
      'group_supervisor_national_code' => $request->group_supervisor_national_code,
    ])->first();
    if ($jahadiGroup) {
      return $this->success(
        null,
        message: 'کد اطلس گروه جهادی ' . $jahadiGroup->group_name . ' ' . $jahadiGroup->group_registeration_number . ' می باشد.',
      );
    } else {
      return $this->error(
        null,
        message: 'گروه جهادی یافت نشد',
        code: 400,
      );
    }
  }

  private function _handleJahadiGroup(RegisterRequest $request)
  {
    $jahadiGroup = JahadiGroups::where(
      'group_supervisor_national_code',
      '=',
      $request->national_code,
    )->first();
    if (!$jahadiGroup) {
      return $this->error(
        null,
        message: 'گروه جهادی یافت نشد',
        code: 400,
      );
    } else if ($jahadiGroup->verify_code_count >= 3) {
      return $this->error(
        null,
        message: 'شما بیش از سه مرتبه درخواست پیامک کد تایید کرده اید و دیگر این امکان را با این شماره همراه ندارید',
        code: 403,
      );
    }
    $verifyCode = strval(rand(11111, 99999));
    $sendSmsResult = $this->_sendVerifySms(
      $request->phone_number,
      $verifyCode,
    );
    if ($sendSmsResult->getStatusCode() == 200) {
      $this->_updateJahadiGroup($jahadiGroup, $request, $verifyCode);
      return $this->success(
        $jahadiGroup->fresh(),
        message: 'پیامک کد تایید ارسال شد',
      );
    } else {
      // Sending SMS failed
      return $this->error(
        null,
        message: 'خطا در ارسال کد تایید. در زمان دیگری امتحان نمایید',
        code: $sendSmsResult->getStatusCode(),
      );
    }
  }

  private function _handleIndividual(RegisterRequest $request)
  {
    $individual = Individuals::where(
      'national_code',
      '=',
      $request->national_code,
    )->first();
    if ($individual) {
      // User registered before
      if ($individual->verify_code_count >= 3) {
        return $this->error(
          null,
          message: 'شما بیش از سه مرتبه درخواست پیامک کد تایید کرده اید و دیگر این امکان را ندارید',
          code: 403,
        );
      }
      $verifyCode = strval(rand(11111, 99999));
      $sendSmsResult = $this->_sendVerifySms($request->phone_number, $verifyCode);
      if ($sendSmsResult->getStatusCode() == 200) {
        $this->_updateIndividualVerifyCode($individual, $request, $verifyCode);
        return $this->success(
          null,
          message: 'پیامک کد تایید ارسال شد',
        );
      } else {
        // Sending SMS failed
        return $this->error(
          null,
          message: 'خطا در ارسال کد تایید. در زمان دیگری امتحان نمایید',
          code: $sendSmsResult->getStatusCode(),
        );
      }
    } else {
      // User registered for the first time
      $verifyCode = strval(rand(11111, 99999));
      $sendSmsResult = $this->_sendVerifySms($request->phone_number, $verifyCode);
      if ($sendSmsResult->getStatusCode() == 200) {
        $this->_createIndividual($request, $verifyCode);
        return $this->success(
          null,
          message: 'پیامک کد تایید ارسال شد',
        );
      } else {
        // Sending SMS failed
        return $this->error(
          null,
          message: 'خطا در ارسال کد تایید. در زمان دیگری امتحان نمایید',
          code: $sendSmsResult->getStatusCode(),
        );
      }
    }
  }

  private function _handleGroups(RegisterRequest $request)
  {
  }

  private function _sendVerifySms(string $phoneNumber, string $verifyCode)
  {
    $client = new Client();

    $url = 'https://api.sms.ir/v1/send/verify';
    $headers = [
      'Accept' => 'application/json',
      'X-API-KEY' => 'kbXRwwN1VYa7bf9BHGZGp3n1IfWHAOWZ4hcirmQFedlbmTzZNHIrTt1QasvGvioC',
    ];
    $body = [
      'mobile' => $phoneNumber,
      'templateId' => 254876,
      "parameters" => [
        [
          "name" => "Code",
          "value" => $verifyCode,
        ]
      ]
    ];

    $sendSmsResponse = $client->request('POST', $url, [
      'headers' => $headers,
      'json' => $body,
    ]);

    return $sendSmsResponse;
  }

  private function _updateJahadiGroup(
    JahadiGroups $jahadiGroup,
    RegisterRequest $request,
    string $verifyCode,
  ) {
    return $jahadiGroup->update([
      'phone_number' => $request->phoneNumber,
      'verify_code_count' => $jahadiGroup->verify_code_count == null ?
        1 : $jahadiGroup->verify_code_count + 1,
      'current_verify_code' => $verifyCode,
      'last_ip' => $request->getClientIp(),
    ]);
  }

  private function _updateIndividualVerifyCode(
    Individuals $individual,
    RegisterRequest $request,
    string $verifyCode,
  ) {
    return $individual->update([
      'phone_number' => $request->phoneNumber,
      'verify_code_count' => $individual->verify_code_count == null ?
        1 : $individual->verify_code_count + 1,
      'current_verify_code' => $verifyCode,
      'last_ip' => $request->getClientIp(),
    ]);
  }

  private function _createIndividual(
    RegisterRequest $request,
    string $verifyCode,
  ) {
    return Individuals::create([
      'national_code' => $request->national_code,
      'phone_number' => $request->phoneNumber,
      'verify_code_count' => 1,
      'current_verify_code' => $verifyCode,
      'last_ip' => $request->getClientIp(),
    ]);
  }
}
