<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefereeOtpRequest;
use App\Models\Referees;
use function sendVerifySms;

class RefereeLoginController extends Controller
{
  public function otp(RefereeOtpRequest $request)
  {
    $request->validated($request->all());
    $referees = Referees::where(
      'phone',
      '=',
      $request->phone_number,
    )->first();
    if (!$referees) {
      return $this->error(
        null,
        message: 'این شماره تلفن به عنوان داور ثبت نشده است',
        code: 403,
      );
    }
    $verifyCode = strval(rand(11111, 99999));
    $sendSmsResult = sendVerifySms($request->phone_number, $verifyCode);
    if ($sendSmsResult->getStatusCode() == 200) {
      $referees->update([
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
}
