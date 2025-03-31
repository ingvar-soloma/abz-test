<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{

    const USER_WITH_THIS_PHONE_OR_EMAIL_ALREADY_EXIST = 'User with this phone or email already exist';

    final public function rules(): array
    {
        //phone - user phone number, should start with code of Ukraine +380
        return [
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^\+380\d{9}$/|unique:users',
            'password' => 'required|string|min:8',
            'position_id' => 'required|integer|exists:positions,id',
        ];
    }

    final public function messages(): array
    {
        return [
            'email.unique' => self::USER_WITH_THIS_PHONE_OR_EMAIL_ALREADY_EXIST,
            'phone.unique' => self::USER_WITH_THIS_PHONE_OR_EMAIL_ALREADY_EXIST,
        ];
    }

    // remove email and phone unique error from the errors array
    final public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $validator->errors()->remove('email');
            $validator->errors()->remove('phone');
        });
    }
}
