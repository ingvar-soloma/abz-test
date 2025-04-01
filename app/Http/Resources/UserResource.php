<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    final public function toArray($request): array
    {
        $data = parent::toArray($request);

        return array_merge($data, [
            'position' => $this->position->name,
            'photo' => $this->photo ? asset("storage/photos/{$this->photo}") : null,]);
    }
}
