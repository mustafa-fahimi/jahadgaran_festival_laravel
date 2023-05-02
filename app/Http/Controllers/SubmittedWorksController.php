<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupSubmittedWorksRequest;
use App\Http\Requests\IndividualSubmittedWorksRequest;
use App\Models\Individuals;
use App\Traits\HttpResponses;
use App\Http\Requests\JahadiGroupSubmittedWorksRequest;
use App\Models\Groups;
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
    $submittedWorksCount = $jahadiGroup->submittedWorks()->count();
    if ($submittedWorksCount >= 3) {
      // Maximum submit
      return $this->error(
        null,
        message: 'نمی توانید بیش از ۳ مرتبه ارسال کنید',
        code: 403,
      );
    }
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
        message: 'اطلاعات با موفقیت ذخیره شد'
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
    $submittedWorksCount = $individual->submittedWorks()->count();
    if ($submittedWorksCount >= 3) {
      // Maximum submit
      return $this->error(
        null,
        message: 'نمی توانید بیش از ۳ مرتبه ارسال کنید',
        code: 403,
      );
    }
    if ($individual->current_verify_code != $request->verify_code) {
      // Wrong verify code
      return $this->error(
        null,
        message: 'کد تایید صحیح نمی باشد',
        code: 403,
      );
    }

    $this->_updateIndividual($individual, $request);
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

  public function groupSubmittedWork(
    GroupSubmittedWorksRequest $request,
  ) {
    $request->validated($request->all());
    $group = Groups::where(
      'group_supervisor_national_code',
      '=',
      $request->group_supervisor_national_code,
    )->first();
    $submittedWorksCount = $group->submittedWorks()->count();
    if ($submittedWorksCount >= 3) {
      // Maximum submit
      return $this->error(
        null,
        message: 'نمی توانید بیش از ۳ مرتبه ارسال کنید',
        code: 403,
      );
    }
    if ($group->current_verify_code != $request->verify_code) {
      // Wrong verify code
      return $this->error(
        null,
        message: 'کد تایید صحیح نمی باشد',
        code: 403,
      );
    }

    $this->_updateGroup($group, $request);
    $storedFileName = $this->storeFileAndReturnName($request->file('file'));
    $isInsertSuccessful = SubmittedWorks::create([
      'group_id' => $group->id,
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

  private function _updateIndividual(
    Individuals $individual,
    IndividualSubmittedWorksRequest $request,
  ) {
    return $individual->update([
      'fname' => $request->fname,
      'lname' => $request->lname,
      'city' => $request->city,
    ]);
  }

  private function _updateGroup(
    Groups $group,
    GroupSubmittedWorksRequest $request,
  ) {
    return $group->update([
      'group_name' => $request->group_name,
      'established_year' => $request->established_year,
      'group_license_number' => $request->group_license_number,
      'group_institution' => $request->group_institution,
      'group_city' => $request->group_city,
      'group_supervisor_fname' => $request->group_supervisor_fname,
      'group_supervisor_lname' => $request->group_supervisor_lname,
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
