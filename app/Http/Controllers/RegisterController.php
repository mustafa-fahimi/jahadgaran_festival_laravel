<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAtlasCodeRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\JahadiGroups;
use App\Traits\HttpResponses;
use GuzzleHttp\Client;

class RegisterController extends Controller
{
  use HttpResponses;

  public function register(RegisterRequest $request)
  {
    $request->validated($request->all());
    $jahadiGroup = JahadiGroups::where(
      'group_supervisor_national_code',
      '=',
      $request->group_supervisor_national_code,
    )->first();

    if ($jahadiGroup) {
      return $this->_handleJahadiGroup($request, $jahadiGroup);
    }
  }

  public function getAtlasCode(GetAtlasCodeRequest $request)
  {
    $jahadiGroup = JahadiGroups::where([
      'group_supervisor_national_code' => $request->group_supervisor_national_code,
    ])->first();
    if ($jahadiGroup) {
      return $this->success(
        [$jahadiGroup->group_name, $jahadiGroup->group_registeration_number],
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

  private function _handleJahadiGroup(
    RegisterRequest $request,
    JahadiGroups $jahadiGroup,
  ) {
    $verifyCode = strval(rand(11111, 99999));
    if ($jahadiGroup->verify_code_count >= 3) {
      return $this->error(
        null,
        message: 'شما بیش از سه مرتبه درخواست پیامک کد تایید کرده اید و دیگر این امکان را با این شماره همراه ندارید',
        code: 403,
      );
    }
    $sendSmsResult = $this->_sendVerifySms($request->phone_number, $verifyCode);
    if ($sendSmsResult->getStatusCode() == 200) {
      $this->_savePhoneAndVerifyCode(
        $jahadiGroup,
        $request->phone_number,
        $verifyCode,
        $request->getClientIp(),
      );
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

  public function _sendVerifySms(string $phoneNumber, string $verifyCode)
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

  public function _savePhoneAndVerifyCode(
    JahadiGroups $jahadiGroup,
    string $phoneNumber,
    string $verifyCode,
    string $ip,
  ) {
    $jahadiGroup->phone_number = $phoneNumber;
    $jahadiGroup->current_verify_code = $verifyCode;
    $jahadiGroup->last_ip = $ip;
    if ($jahadiGroup->verify_code_count == null) {
      $jahadiGroup->verify_code_count = 1;
    } else {
      $jahadiGroup->verify_code_count = $jahadiGroup->verify_code_count + 1;
    }
    return $jahadiGroup->update();
  }
}
