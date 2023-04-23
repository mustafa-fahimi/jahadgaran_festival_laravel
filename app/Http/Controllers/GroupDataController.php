<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAtlasCodeRequest;
use App\Http\Requests\GroupDataRequest;
use App\Models\GroupData;
use App\Traits\HttpResponses;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;

class GroupDataController extends Controller
{
  use HttpResponses;

  public function index(GroupDataRequest  $request)
  {
    $request->validated($request->all());
    $groupData = GroupData::where([
      'group_registeration_number' => $request->group_registeration_number,
      'group_supervisor_national_code' => $request->group_supervisor_national_code,
    ])->first();

    if ($groupData) {
      $verifyCode = strval(rand(11111, 99999));
      if ($groupData->verify_code_count >= 3) {
        return $this->error(
          null,
          message: 'شما بیش از سه مرتبه درخواست پیامک کد تایید کرده اید و دیگر این امکان را با این شماره همراه ندارید',
          code: 403,
        );
      }
      $sendSmsResult = $this->_sendVerifySms(
        $request->phone_number,
        $verifyCode,
      );
      if ($sendSmsResult->getStatusCode() == 200) {
        $saveVerifyCodeResult = $this->_savePhoneAndVerifyCode(
          $groupData,
          $request->phone_number,
          $verifyCode,
          $request->getClientIp(),
        );
        return $this->success(
          $groupData->fresh(),
          message: 'پیامک کد تایید ارسال شد' . $saveVerifyCodeResult,
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
      return $this->error(
        null,
        message: 'کد گروه با کدملی مسئول گروه همخوانی ندارد',
        code: 400,
      );
    }
  }

  public function getAtlasCode(GetAtlasCodeRequest $request)
  {
    $groupData = GroupData::where([
      'group_supervisor_national_code' => $request->group_supervisor_national_code,
    ])->first();
    if ($groupData) {
      return $this->success(
        [$groupData->group_name, $groupData->group_registeration_number],
        message: 'کد اطلس گروه جهادی ' . $groupData->group_name . ' ' . $groupData->group_registeration_number . ' می باشد.',
      );
    } else {
      return $this->error(
        null,
        message: 'گروه جهادی یافت نشد',
        code: 400,
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
      'templateId' => 100000,
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
    GroupData $groupData,
    string $phoneNumber,
    string $verifyCode,
    string $ip,
  ) {
    $groupData->phone_number = $phoneNumber;
    $groupData->current_verify_code = $verifyCode;
    $groupData->last_ip = $ip;
    if ($groupData->verify_code_count == null) {
      $groupData->verify_code_count = 1;
    } else {
      $groupData->verify_code_count = $groupData->verify_code_count + 1;
    }
    return $groupData->update();
  }
}
