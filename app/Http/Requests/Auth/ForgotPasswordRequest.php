<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'emailormobile' => [
                'required',
                'max:100',
                // Same shape: "does this account exist" is a closure rule here for the same reason
                // LoginRequest uses one - a single flexible email/phone/name identifier rather than
                // separate fields, so Rule::exists() can't express it directly.
                function ($attribute, $value, $fail) {
                    if (trim((string) $value) === '') {
                        return; // "required" above already reports this
                    }
                    $user = User::findByLoginIdentifier($value);
                    if (! $user) {
                        $fail("No account was found with that email, phone number or name ({$value}). Please check and try again.");
                        return;
                    }
                    if (! $user->email) {
                        $fail('This account has no email on file. Please contact support for help resetting your password.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'emailormobile.required' => 'Please enter your email, phone number or name.',
        ];
    }

    protected function failedValidation(ValidatorContract $validator)
    {
        $exception = new ValidationException($validator);
        $exception->errorBag = $this->errorBag;
        $exception->redirectTo = $this->getRedirectUrl();

        if ($this->ajax() || $this->wantsJson()) {
            $exception->response = response()->json([
                'status' => 'errors',
                'datas' => $validator->errors(),
            ]);

            throw $exception;
        }

        $exception->response = redirect($exception->redirectTo)
            ->withInput($this->only('emailormobile'))
            ->withErrors($validator, $exception->errorBag);

        throw $exception;
    }
}
