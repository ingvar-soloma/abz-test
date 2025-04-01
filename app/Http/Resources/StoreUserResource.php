<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreUserResource extends JsonResource
{
    final public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => 'New user successfully registered',
        ];
    }
}
