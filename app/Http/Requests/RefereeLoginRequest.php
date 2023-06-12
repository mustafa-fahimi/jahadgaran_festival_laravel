<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use App\Traits\HttpResponses;

class RefereeLoginRequest extends FormRequest
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
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
   */
  public function rules(): array
  {
    return [
      'phone_number' => ['required', 'string', 'min:11'],
      'verify_code' => ['required', 'string'],
    ];
  }
}