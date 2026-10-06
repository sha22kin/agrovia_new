<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'digits:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Please enter the 6-digit code.',
            'code.digits' => 'The code must be exactly 6 digits.',
        ];
    }

    protected function failedValidation(ValidatorContract $validator)
    {
        $exception = new ValidationException($validator);
        $exception->errorBag = $this->errorBag;

        // Both callers of this request - the register-verify page's form and the Login-with-OTP tab - are
        // AJAX-only (there is no server-rendered fallback view for a 6-digit code the way login/register
        // have), so failedValidation() only ever needs to answer in the JSON shape their JS already expects.
        $exception->response = response()->json([
            'status' => 'errors',
            'datas' => $validator->errors(),
        ]);

        throw $exception;
    }
}
