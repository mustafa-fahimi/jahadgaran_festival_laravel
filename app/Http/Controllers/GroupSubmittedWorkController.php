<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use App\Models\GroupData;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GroupSubmittedWorkController extends Controller
{
    use HttpResponses;

    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateDataForStore($request);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'message' => 'اطلاعات ارسال شده صحیح نمی باشد',
                    'errors' => $e->errors(),
                ],
                400,
            );
        }

        $groupCodeExist = $this->checkDuplicateGroupCode($validatedData['group_code']);
        $phoneNumberExist = $this->checkDuplicatePhoneNumber($validatedData['supervisor_phone']);
        if ($groupCodeExist) {
            return response()->json(
                [
                    'message' => 'اطلاعات این گروه جهادی قبلا ثبت شده است',
                ],
                422,
            );
        } else if ($phoneNumberExist) {
            return response()->json(
                [
                    'message' => 'این شماره همراه قبلا ثبت شده است',
                ],
                422,
            );
        }
        $validatedData['file_name'] = $this->storeFileAndReturnName($request);

        GroupData::create($validatedData);
        return response()->json(
            [
                'message' => 'اطلاعات شما با موفقیت ثبت گردید',
            ],
            201,
        );
    }

    private function validateDataForStore(Request $request)
    {
        return $request->validate([
            'group_name' => 'required',
            'group_code' => 'required|numeric',
            'supervisor_fname' => 'required',
            'supervisor_lname' => 'required',
            'supervisor_phone' => 'required',
            'attachment_type' => 'required',
            'file' => 'required|file',
        ]);
    }

    private function storeFileAndReturnName(Request $request)
    {
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $newName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $extension;
        $file->storeAs('uploads', $newName);
        return $newName;
    }

    private function checkDuplicateGroupCode($groupCode)
    {
        return count(GroupData::where(
            'group_code',
            '=',
            $groupCode,
        )->get()) > 0;
    }

    private function checkDuplicatePhoneNumber($supervisorPhone)
    {
        return count(GroupData::where(
            'supervisor_phone',
            '=',
            $supervisorPhone,
        )->get()) > 0;
    }
}
