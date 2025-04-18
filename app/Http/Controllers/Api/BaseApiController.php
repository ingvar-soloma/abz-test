<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseCollection;
use App\Http\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

abstract class BaseApiController extends Controller
{
    protected string|false $collectionClass = BaseCollection::class;
    protected string $resourceClass;
    protected BaseService $service;
    protected bool $pagination = true;
    abstract protected function getService(): BaseService;
    abstract protected function getRequestClass(string $method): string;

    protected function getRelations(string $method): array
    {
        return match ($method) {
            'update' => [],
            'store' => [],
            'index' => [],
            'show' => [],
            default => throw new \InvalidArgumentException("Unknown method for request class resolution"),
        };
    }

    protected function getStoreResource(): string
    {
        return $this->resourceClass;
    }

    // Implement common CRUD operations using abstract methods
    public function store(Request $request): JsonResponse
    {
        $this->service = $this->getService();
//        Gate::authorize('create', $this->service->getModel());

        $requestClass = $this->getRequestClass(__FUNCTION__);
        $validated = app($requestClass)->validated();
        $with = $this->getRelations(__FUNCTION__);

        $model = $this->service->store($validated);
        $model->load($with);

        $resourceClass = $this->getStoreResource();
        return response()->json(new $resourceClass($model), 201);
    }

    final public function index(Request $request): JsonResponse
    {
        // if collection is defined in the service, it will be used
        $this->service = $this->getService();
//        Gate::authorize('viewAny', $this->service->getModel());

        $requestClass = $this->getRequestClass(__FUNCTION__);
        $validated = app($requestClass)->validated();
        $with = $this->getRelations(__FUNCTION__);

        $data = $this->service->getAllData($validated, $with);

        $collectionClass = $this->collectionClass;
        if ($collectionClass) {
            return response()->json(new $collectionClass($data));
        }

        $resourceClass = $this->resourceClass;
        return response()->json($resourceClass::collection($data));
    }

    final public function show(int $id): JsonResponse
    {
        $with = $this->getRelations(__FUNCTION__);

        $this->service = $this->getService();
        $model = $this->service->show($id);
//        Gate::authorize('view', $model);
        $model->load($with);

        $resourceClass = $this->resourceClass;
        $modalName = strtolower(class_basename($model));
        return response()->json(['success' => true, $modalName => new $resourceClass($model)]
        );
    }

    /**
     * @throws \Exception
     */
    final public function update(Request $request, int $id): JsonResponse
    {
        $requestClass = $this->getRequestClass(__FUNCTION__);
        $validated = app($requestClass)->validated();
        $with = $this->getRelations(__FUNCTION__);

        $this->service = $this->getService();
        $model = $this->service->show($id);
        Gate::authorize('update', $model);
        $model = $this->service->update($validated, $model);
        $model->load($with);

        $resourceClass = $this->resourceClass;
        $modalName = strtolower(class_basename($model));
        return response()->json(['success' => true, $modalName => new $resourceClass($model)]);
    }

    /**
     * @throws \Exception
     */
    final public function destroy(int $id): JsonResponse
    {
        $this->service = $this->getService();
        $model = $this->service->show($id);
        Gate::authorize('delete', $model);
        $data = $this->service->destroy($model);

        return response()->json(['success' => $data]);
    }

}
