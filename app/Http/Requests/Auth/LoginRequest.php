<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'max:100',
                // Agroviea's login field is a single email/phone/name identifier rather than TaxBridge's
                // separate email/mobile fields, so the "does this account exist" check (still a shape-level
                // existence check, not business logic) is a closure rule instead of Rule::exists() - the
                // password itself is never checked here, that needs Hash::check() and stays in the controller.
                function ($attribute, $value, $fail) {
                    if (trim((string) $value) === '') {
                        return; // "required" above already reports this
                    }
                    if (! User::findByLoginIdentifier($value)) {
                        $fail("No account was found with that email, phone number or name ({$value}). Please check and try again.");
                    }
                },
            ],
            'password' => ['required', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Please enter your email, phone number or name.',
            'password.required' => 'Please enter your password.',
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
            ->withInput($this->only('username')) // never the password field
            ->withErrors($validator, $exception->errorBag);

        throw $exception;
    }
}
