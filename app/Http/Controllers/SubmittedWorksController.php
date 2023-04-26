<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndividualSubmittedWorksRequest;
use App\Models\Individuals;
use App\Traits\HttpResponses;
use App\Http\Requests\JahadiGroupSubmittedWorksRequest;
use App\Models\JahadiGroups;
use App\Models\SubmittedWorks;

class SubmittedWorksController extends Controller
{
  use HttpResponses;

  public function jahadiGroupSubmittedWork(
    JahadiGroupSubmittedWorksRequest $request,
  ) {
    $request->validated($request->all());
    $jahadiGroup = JahadiGroups::where(
      'group_supervisor_national_code',
      '=',
      $request->national_code,
    )->first();
    if ($jahadiGroup->current_verify_code != $request->verify_code) {
      // Wrong verify code
      return $this->error(
        null,
        message: 'کد تایید صحیح نمی باشد',
        code: 403,
      );
    }

    $storedFileName = $this->storeFileAndReturnName($request->file('file'));
    $isInsertSuccessful = SubmittedWorks::create([
      'jahadi_group_id' => $jahadiGroup->id,
      'attachment_type' => $request->attachment_type,
      'description' => $request->description,
      'file_path' => $storedFileName,
    ]);
    if ($isInsertSuccessful) {
      return $this->success(
        null,
        message: 'اطلاعات با موفقیت ذخیره شد.'
      );
    } else {
      return $this->error(
        null,
        message: 'خطا در سرور! مجددا امتحان نمایید',
        code: 422,
      );
    }
  }

  public function individualSubmittedWork(
    IndividualSubmittedWorksRequest $request,
  ) {
    $request->validated($request->all());
    $individual = Individuals::where(
      'national_code',
      '=',
      $request->national_code,
    )->first();
    if ($individual->current_verify_code != $request->verify_code) {
      // Wrong verify code
      return $this->error(
        null,
        message: 'کد تایید صحیح نمی باشد',
        code: 403,
      );
    }

    $this->_updateIndividualVerifyCode($individual, $request);
    $storedFileName = $this->storeFileAndReturnName($request->file('file'));
    $isInsertSuccessful = SubmittedWorks::create([
      'individual_id' => $individual->id,
      'attachment_type' => $request->attachment_type,
      'description' => $request->description,
      'file_path' => $storedFileName,
    ]);
    if ($isInsertSuccessful) {
      return $this->success(
        null,
        message: 'اطلاعات با موفقیت ذخیره شد.'
      );
    } else {
      return $this->error(
        null,
        message: 'خطا در سرور! مجددا امتحان نمایید',
        code: 422,
      );
    }
  }

  private function _updateIndividualVerifyCode(
    Individuals $individual,
    IndividualSubmittedWorksRequest $request,
  ) {
    return $individual->update([
      'fname' => $request->fname,
      'lname' => $request->lname,
      'city' => $request->city,
    ]);
  }

  private function storeFileAndReturnName($file)
  {
    $originalName = $file->getClientOriginalName();
    $extension = $file->getClientOriginalExtension();
    $newName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $extension;
    $file->storeAs('uploads', $newName);
    return $newName;
  }
}