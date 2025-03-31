<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    final public function toArray($request): array
    {
        return [
            'success' => true,
            'page' => $this->currentPage(),
            'count' => $this->count(),
            'total_pages' => $this->lastPage(),
            'total_users' => $this->total(),
            'links' => [
                'next_url' => $this->nextPageUrl(),
                'prev_url' => $this->previousPageUrl(),
            ],
            'users' => UserResource::collection($this->collection),
        ];
    }
}
