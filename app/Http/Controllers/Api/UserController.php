<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\ReadUserRequest;
use App\Http\Requests\ShowUserRequest;
use App\Http\Resources\StoreUserResource;
use App\Http\Resources\UserCollection;
use App\Http\Services\UserService;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    final protected function getRelations(string $method): array
    {
        return match ($method) {
            'store' => [],
            'index' => ['position'],
            'show' => [],
            default => throw new \InvalidArgumentException("Unknown method for request class resolution"),
        };
    }

    final protected function getService(): UserService
    {
        return resolve(UserService::class);

    }

    final protected function getStoreResource(): string
    {
        return StoreUserResource::class;
    }

    final public function store(Request $request): JsonResponse
    {
        $this->service = $this->getService();

        $requestClass = $this->getRequestClass(__FUNCTION__);
        $validated = app($requestClass)->validated();
        $with = $this->getRelations(__FUNCTION__);

        $model = $this->service->registerUser($validated, $request->bearerToken());
        $model->load($with);

        $resourceClass = $this->getStoreResource();
        return response()->json(new $resourceClass($model), 201);
    }

}
