<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupSubmittedWorkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'group_registeration_number' => ['required', 'string'],
            'group_supervisor_national_code' => ['required', 'string'],
            'verify_code' => ['required', 'string'],
            'attachment_type' => ['required', 'string'],
            'description' => ['string'],
            'file' => ['required', 'file'],
        ];
    }
}
