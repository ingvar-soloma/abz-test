<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
{
    final public function toArray($request): array
    {
        $data = parent::toArray($request);


        return array_merge($data, []);
    }


}
