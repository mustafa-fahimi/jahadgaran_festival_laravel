<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefereeOtpRequest;
use App\Http\Requests\RefereeLoginRequest;
use App\Models\Referees;
use function sendVerifySms;

class RefereeLoginController extends Controller
{
  public function otp(RefereeOtpRequest $request)
  {
    $request->validated($request->all());
    $referee = Referees::where(
      'phone',
      '=',
      $request->phone_number,
    )->first();
    if (!$referee) {
      return $this->error(
        null,
        message: 'این شماره تلفن به عنوان داور ثبت نشده است',
        code: 403,
      );
    }
    $verifyCode = strval(rand(11111, 99999));
    $sendSmsResult = sendVerifySms($request->phone_number, $verifyCode);
    if ($sendSmsResult->getStatusCode() == 200) {
      $referee->update([
        'current_verify_code' => $verifyCode,
      ]);
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

  public function login(RefereeLoginRequest $request)
  {
    // $request->validated($request->all());
    $referee = Referees::where(
      'phone',
      '=',
      $request->phone_number,
    )->first();
    if ($referee->current_verify_code != $request->verify_code) {
      // Wrong verify code
      return $this->error(
        null,
        message: 'کد تایید صحیح نمی باشد',
        code: 403,
      );
    }
    $generatedToken = $this->generateToken();
    $referee->update([
      'current_verify_code' => null,
      'token' => $generatedToken,
    ]);
    return $this->success(
      [
        'token' => $generatedToken
      ],
      message: 'ورود با موفقیت انجام شد',
    );
  }

  function generateToken()
  {
    $randomString = bin2hex(random_bytes(16));
    $tokenExist = Referees::where(
      'token',
      '=',
      $randomString,
    )->first();
    if (!$tokenExist) {
      return $randomString;
    } else {
      $this->generateToken();
    }
  }
}
