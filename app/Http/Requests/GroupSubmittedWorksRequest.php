<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupSubmittedWorksRequest extends FormRequest
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
      'group_supervisor_national_code' => ['required', 'string'],
      'group_name' => ['required', 'string'],
      'established_year' => ['required', 'integer'],
      'group_institution' => ['required', 'string'],
      'group_city' => ['required', 'string'],
      'group_supervisor_fname' => ['required', 'string'],
      'group_supervisor_lname' => ['required', 'string'],
      'verify_code' => ['required', 'string'],
      'attachment_type' => ['required', 'string'],
      'file' => ['required', 'file'],
    ];
  }
}