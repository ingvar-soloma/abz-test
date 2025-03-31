<?php

namespace App\Http\Repositories;

use App\Interfaces\ModelRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class BaseRepository implements ModelRepository
{
    protected static Model $model;

    public function __construct(Model $model)
    {
        self::$model = $model;
    }

    public function create(array $validated): Model
    {
        return self::$model::create($validated);
    }

    public function getAll(array $params, array $with = [], array $fields = ['*']): Collection
    {
        $query = self::$model::select($fields)->with($with);

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }

        return $query->get();
    }

    // add index with pagination
    public function getAllPaginated(
        array $params,
        int $count = 5,
        int $page = 1,
        array $with = [],
        array $fields = ['*']
    ): LengthAwarePaginator
    {
        return self::$model::select($fields)->with($with)->paginate($count, ['*'], 'page', $page);
    }

    public function show(int $id): Model
    {
        return self::$model::findOrFail($id);
    }

    /**
     * @throws \Exception
     * */
    public function update(int|Model $id, array $validated): Model
    {
        $modelInstance = is_int($id) ? $this->show($id) : $id;
        if ($modelInstance->update($validated)) {
            return $modelInstance;
        }
        else {
            throw new \Exception('Update failed');
        }
    }

    public function delete(int|Model $id): ?bool
    {
        $modelInstance = is_int($id) ? $this->show($id) : $id;
        return $modelInstance->delete();
    }

    public static function getModel(): Model
    {
        return self::$model;
    }
}
