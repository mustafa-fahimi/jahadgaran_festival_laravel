<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Groups;
use App\Models\Individuals;
use App\Models\JahadiGroups;
use function sendVerifySms;


class LoginController extends Controller
{
  public function registerJahadiGroup(LoginRequest $request)
  {
    $request->validated($request->all());
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
    } else if ($jahadiGroup->verify_code_count >= 12) {
      return $this->error(
        null,
        message: 'شما بیش از ۱۲ مرتبه درخواست پیامک کد تایید کرده اید و دیگر این امکان را با این شماره همراه ندارید',
        code: 403,
      );
    }
    $verifyCode = strval(rand(11111, 99999));
    $sendSmsResult = sendVerifySms($request->phone_number, $verifyCode);
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

  public function registerIndividual(LoginRequest $request)
  {
    $request->validated($request->all());
    $group = Groups::where(
      'group_supervisor_national_code',
      '=',
      $request->national_code,
    )->first();
    if ($group) {
      return $this->error(
        null,
        message: 'گروهی با این کد ملی ثبت شده است. لطفا به عنوان گروه وارد شوید',
        code: 400,
      );
    }
    $individual = Individuals::where(
      'national_code',
      '=',
      $request->national_code,
    )->first();
    if ($individual) {
      // User registered before
      if ($individual->verify_code_count >= 12) {
        return $this->error(
          null,
          message: 'شما بیش از ۱۲ مرتبه درخواست پیامک کد تایید کرده اید و دیگر این امکان را ندارید',
          code: 403,
        );
      }
      $verifyCode = strval(rand(11111, 99999));
      $sendSmsResult = sendVerifySms($request->phone_number, $verifyCode);
      if ($sendSmsResult->getStatusCode() == 200) {
        $this->_updateIndividual($individual, $request, $verifyCode);
        return $this->success(
          $individual->fresh(),
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
      $sendSmsResult = sendVerifySms($request->phone_number, $verifyCode);
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

  public function registerGroup(LoginRequest $request)
  {
    $request->validated($request->all());
    $individual = Individuals::where(
      'national_code',
      '=',
      $request->national_code,
      ''
    )->first();
    if ($individual) {
      return $this->error(
        null,
        message: 'شخص حقیقی با این کد ملی ثبت شده است. لطفا به عنوان شخص حقیقی وارد شوید',
        code: 400,
      );
    }
    $group = Groups::where(
      'group_supervisor_national_code',
      '=',
      $request->national_code,
    )->first();
    if ($group) {
      // Group registered before
      if ($group->verify_code_count >= 12) {
        return $this->error(
          null,
          message: 'شما بیش از ۱۲ مرتبه درخواست پیامک کد تایید کرده اید و دیگر این امکان را ندارید',
          code: 403,
        );
      }
      $verifyCode = strval(rand(11111, 99999));
      $sendSmsResult = sendVerifySms($request->phone_number, $verifyCode);
      if ($sendSmsResult->getStatusCode() == 200) {
        $this->_updateGroup($group, $request, $verifyCode);
        return $this->success(
          $group->fresh(),
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
      $sendSmsResult = sendVerifySms($request->phone_number, $verifyCode);
      if ($sendSmsResult->getStatusCode() == 200) {
        $this->_createGroup($request, $verifyCode);
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



  private function _updateJahadiGroup(
    JahadiGroups $jahadiGroup,
    LoginRequest $request,
    string $verifyCode,
  ) {
    return $jahadiGroup->update([
      'phone_number' => $request->phone_number,
      'verify_code_count' => $jahadiGroup->verify_code_count == null ?
        1 : $jahadiGroup->verify_code_count + 1,
      'current_verify_code' => $verifyCode,
      'last_ip' => $request->getClientIp(),
    ]);
  }

  private function _createIndividual(
    LoginRequest $request,
    string $verifyCode,
  ) {
    return Individuals::create([
      'fname' => '',
      'lname' => '',
      'city' => '',
      'national_code' => $request->national_code,
      'phone_number' => $request->phone_number,
      'verify_code_count' => 1,
      'current_verify_code' => $verifyCode,
      'last_ip' => $request->getClientIp(),
    ]);
  }

  private function _updateIndividual(
    Individuals $individual,
    LoginRequest $request,
    string $verifyCode,
  ) {
    return $individual->update([
      'phone_number' => $request->phone_number,
      'verify_code_count' => $individual->verify_code_count == null ?
        1 : $individual->verify_code_count + 1,
      'current_verify_code' => $verifyCode,
      'last_ip' => $request->getClientIp(),
    ]);
  }

  private function _createGroup(
    LoginRequest $request,
    string $verifyCode,
  ) {
    return Groups::create([
      'group_name' => '',
      'group_institution' => '',
      'group_city' => '',
      'group_supervisor_fname' => '',
      'group_supervisor_lname' => '',
      'group_supervisor_national_code' => $request->national_code,
      'phone_number' => $request->phone_number,
      'verify_code_count' => 1,
      'current_verify_code' => $verifyCode,
      'last_ip' => $request->getClientIp(),
    ]);
  }

  private function _updateGroup(
    Groups $group,
    LoginRequest $request,
    string $verifyCode,
  ) {
    return $group->update([
      'phone_number' => $request->phone_number,
      'verify_code_count' => $group->verify_code_count == null ?
        1 : $group->verify_code_count + 1,
      'current_verify_code' => $verifyCode,
      'last_ip' => $request->getClientIp(),
    ]);
  }
}
