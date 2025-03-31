<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowUserRequest extends FormRequest
{
    final public function rules(): array
    {
        return [
            'userId' => 'integer|exists:users,id',
        ];
    }
}


