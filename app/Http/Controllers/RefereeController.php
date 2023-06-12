<?php

namespace App\Http\Controllers;

use App\Models\Referees;
use App\Models\SubmittedWorks;
use App\Models\Scores;
use Illuminate\Http\Request;
use App\Http\Requests\RefereeSubmitScoreRequest;

class RefereeController extends Controller
{
  public function submittedWorks(Request $request)
  {
    $referee = Referees::where(
      'token',
      '=',
      $request->header('Authorization'),
    )->first();
    if (!$request->header('Authorization') || !$referee) {
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
  public function submitScore(RefereeSubmitScoreRequest $request)
  {
    $referee = Referees::where(
      'token',
      '=',
      $request->header('Authorization'),
    )->first();
    if (!$request->header('Authorization') || !$referee) {
      return $this->error(
        null,
        message: 'توکن شما اشتباه است لطفا مجددا وارد شوید',
        code: 403,
      );
    }
    $submittedWork = SubmittedWorks::where(
      'id',
      '=',
      $request->submitted_works_id,
    )->first();
    if (!$submittedWork) {
      return $this->error(
        null,
        message: 'اثری با این شناسه یافت نشد',
        code: 404,
      );
    }
    Scores::create([
      'referees_id' => $referee->id,
      'submitted_works_id' => $request->submitted_works_id,
      'score' => $request->score,
      'description' => $request->description,
    ]);
    return $this->success(
      null,
      message: 'امتیاز شما با موفقیت ثبت شد',
    );
  }
}
