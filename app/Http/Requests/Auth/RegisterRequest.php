<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // "01712 345-678" and "01712345678" are the same number (login by mobile compares the plain digits)
        if ($this->filled('mobile')) {
            $this->merge(['mobile' => preg_replace('/[\s\-()]/', '', $this->mobile)]);
        }
        // trim so "   " doesn't pass "required" as a name, and "a@b.com " doesn't fail the unique check by accident
        if ($this->filled('name')) {
            $this->merge(['name' => trim(preg_replace('/\s+/', ' ', $this->name))]);
        }
        if ($this->filled('email')) {
            $this->merge(['email' => trim($this->email)]);
        }

        // an account left mid-way through email verification more than an hour ago is abandoned: free up
        // its email/phone instead of telling the next person "already registered" forever
        User::whereNull('email_verified_at')
            ->where('created_at', '<', now()->subHour())
            ->where(function ($query) {
                $query->where('email', $this->email)->orWhere('mobile', $this->mobile);
            })
            ->delete();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'mobile' => ['required', 'max:20', 'regex:/^\+?[0-9]{6,19}$/', 'unique:users,mobile'],
            'password' => ['required', 'string', 'min:6', 'max:100'],
            'role' => ['nullable', 'in:'.implode(',', array_keys(User::registerRoles()))],
        ];
    }

    public function attributes(): array
    {
        return ['mobile' => 'phone number'];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered. Please login instead.',
            'mobile.unique' => 'This phone number is already registered. Please login instead.',
            'mobile.regex' => 'Please enter a valid phone number.',
            'role.in' => 'Please choose how you want to join.',
            'password.min' => 'Password must be at least 6 characters.',
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
            ->withInput($this->except('_token'))
            ->withErrors($validator, $exception->errorBag);

        throw $exception;
    }
}
