<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmptyRequest extends FormRequest
{
    final public function rules(): array
    {
        return [];
    }
}
