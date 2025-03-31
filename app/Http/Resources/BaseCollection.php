<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class BaseCollection extends ResourceCollection
{
    final public function toArray($request): array
    {

        $className = strtolower(class_basename($this->collection->first()));
        $className = Str::plural($className);


        return [
            'success' => true,
            $className => $this->collection,
        ];
    }
}
