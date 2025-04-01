<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{

    const USER_WITH_THIS_PHONE_OR_EMAIL_ALREADY_EXIST = 'User with this phone or email already exist';

    final public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^\+380\d{9}$/|unique:users',
            'password' => 'required|string|min:8',
            'position_id' => 'required|integer|exists:positions,id',
            'photo' => 'required|file|image|mimes:jpeg,jpg|dimensions:min_width=70,min_height=70|max:5120',
        ];
    }

    final public function messages(): array
    {
        return [
            'email.unique' => self::USER_WITH_THIS_PHONE_OR_EMAIL_ALREADY_EXIST,
            'phone.unique' => self::USER_WITH_THIS_PHONE_OR_EMAIL_ALREADY_EXIST,
            'phone.regex' => 'The phone number must be in the format +380XXXXXXXXX',
        ];
    }


    final public function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();


        // Check if the error contains "email.unique" or "phone.unique"
        if ($errors->has('email') && $errors->first('email') === self::USER_WITH_THIS_PHONE_OR_EMAIL_ALREADY_EXIST ||
            $errors->has('phone') && $errors->first('phone') === self::USER_WITH_THIS_PHONE_OR_EMAIL_ALREADY_EXIST) {

            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => self::USER_WITH_THIS_PHONE_OR_EMAIL_ALREADY_EXIST,
            ], 409));
        }

        // Default validation response (422 Unprocessable Entity)
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'fails' => $errors,
        ], 422));
    }

}
