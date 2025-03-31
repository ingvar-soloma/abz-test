<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EmptyRequest;
use App\Http\Resources\PositionResource;
use App\Http\Services\PositionService;

class PositionController extends BaseApiController
{
    protected string $resourceClass = PositionResource::class;

    final protected function getRequestClass(string $method): string
    {
        return match ($method) {
            'index' => EmptyRequest::class,
            default => throw new \InvalidArgumentException("Unknown method for request class resolution"),
        };
    }


    final protected function getService(): PositionService
    {
        return resolve(PositionService::class);

    }

}
