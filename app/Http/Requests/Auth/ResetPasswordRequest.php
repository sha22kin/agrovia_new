<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // The code itself is checked on its own page (VerifyOtpRequest handles that one) - by the time this
    // request is resolved, resolveVerifiedPasswordResetUser() has already confirmed the code was correct.
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:6', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.min' => 'Password must be at least 6 characters.',
        ];
    }

    protected function failedValidation(ValidatorContract $validator)
    {
        $exception = new ValidationException($validator);
        $exception->errorBag = $this->errorBag;

        // Same as VerifyOtpRequest: this page is AJAX-only, no server-rendered fallback form.
        $exception->response = response()->json([
            'status' => 'errors',
            'datas' => $validator->errors(),
        ]);

        throw $exception;
    }
}
