<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use App\Http\Requests\SubmittedWorksRequest;
use App\Models\JahadiGroups;
use App\Models\SubmittedWorks;

class SubmittedWorksController extends Controller
{
    use HttpResponses;

    public function store(SubmittedWorksRequest $request)
    {
        $request->validated($request->all());
        $jahadiGroup = JahadiGroups::where([
            'group_registeration_number' => $request->group_registeration_number,
            'group_supervisor_national_code' => $request->group_supervisor_national_code,
        ])->first();
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
            'group_id' => $jahadiGroup->id,
            'attachment_type' => $request->attachment_type,
            'description' => $request->description,
            'file_path' => $storedFileName,
        ]);
        if ($isInsertSuccessful) {
            return $this->success([]);
        } else {
            return $this->error(
                null,
                message: 'خطا در سرور! مجددا امتحان نمایید',
                code: 422,
            );
        }
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
