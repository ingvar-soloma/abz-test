<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReadUserRequest extends FormRequest
{
    final public function rules(): array
    {
        return [
            'page' => 'integer|min:1',
            'count' => 'integer|min:1|max:100',
        ];
    }
}


