<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use App\Traits\HttpResponses;

class IndividualSubmittedWorksRequest extends FormRequest
{
  use HttpResponses;
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  protected function failedValidation(Validator $validator)
  {
    $errors = $validator->errors()->toArray();
    throw new HttpResponseException(
      $this->error(
        $errors,
        message: 'اطلاعات ارسال شده صحیح نمی باشد',
        code: Response::HTTP_UNPROCESSABLE_ENTITY,
      ),
    );
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
   */
  public function rules(): array
  {
    return [
      'national_code' => ['required', 'string'],
      'fname' => ['required', 'string'],
      'lname' => ['required', 'string'],
      'city' => ['required', 'string'],
      'verify_code' => ['required', 'string'],
      'attachment_type' => ['required', 'string'],
      'file' => ['required', 'file'],
    ];
  }
}
