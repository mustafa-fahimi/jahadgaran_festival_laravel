<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefereeRequest;
use App\Models\Referees;
use App\Models\SubmittedWorks;

class RefereeController extends Controller
{
  public function submittedWorks(RefereeRequest $request)
  {
    $referee = Referees::where(
      'token',
      '=',
      $request->token,
    )->first();
    if (!$referee) {
      return $this->error(
        null,
        message: 'توکن شما اشتباه است لطفا مجددا وارد شوید',
        code: 403,
      );
    }
    $submittedWorks = SubmittedWorks::where(
      'attachment_type',
      '=',
      $referee->role,
    )->with('scores')->get();
    if (count($submittedWorks) == 0) {
      return $this->error(
        null,
        message: 'اثری با قالب تخصصی شما ثبت نشده است',
        code: 404,
      );
    }
    return $this->success(
      $submittedWorks,
      message: 'اطلاعات با موفقیت دریافت شد',
    );
  }
}
