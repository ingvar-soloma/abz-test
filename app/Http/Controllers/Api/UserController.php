<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\ReadUserRequest;
use App\Http\Requests\ShowUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Services\UserService;
use App\Http\Resources\UserResource;

class UserController extends BaseApiController
{
    protected string|false $collectionClass = UserCollection::class;
    protected string $resourceClass = UserResource::class;

    final protected function getRequestClass(string $method): string
    {
        return match ($method) {
            'store' => StoreUserRequest::class,
            'index' => ReadUserRequest::class,
            'show' => ShowUserRequest::class,
            default => throw new \InvalidArgumentException("Unknown method for request class resolution"),
        };
    }


    final protected function getService(): UserService
    {
        return resolve(UserService::class);

    }

}
