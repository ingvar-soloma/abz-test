<?php

namespace App\Http\Services;

use App\Http\Repositories\PositionRepository;
use App\Models\Positions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class PositionService extends BaseService
{
    public function __construct(readonly protected PositionRepository $repository,)
    {
    }

    final public function getAllData(array $validated, array $with): Collection
    {
        $collection = parent::getAllData($validated, $with);

        if ($collection->isEmpty()) {
            $error = new ModelNotFoundException('No positions found');
            $error->setModel(Positions::class);
            throw $error;
        }

        return $collection;
    }
}
