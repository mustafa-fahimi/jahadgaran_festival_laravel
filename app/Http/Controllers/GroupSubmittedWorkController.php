<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use App\Http\Requests\GroupSubmittedWorkRequest;
use App\Models\GroupData;
use App\Models\GroupSubmittedWork;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GroupSubmittedWorkController extends Controller
{
    use HttpResponses;

    public function store(GroupSubmittedWorkRequest $request)
    {
        $request->validated($request->all());
        $groupData = GroupData::where([
            'group_registeration_number' => $request->group_registeration_number,
            'group_supervisor_national_code' => $request->group_supervisor_national_code,
        ])->first();
        if ($groupData->current_verify_code != $request->verify_code) {
            // Wrong verify code
            return $this->error(
                null,
                message: 'کد تایید صحیح نمی باشد',
                code: 403,
            );
        }

        $storedFileName = $this->storeFileAndReturnName($request->file('file'));
        $isInsertSuccessful = GroupSubmittedWork::create([
            'group_id' => $groupData->id,
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
