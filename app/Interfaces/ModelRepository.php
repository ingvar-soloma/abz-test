<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ModelRepository
{
    public function create(array $validated): Model;
    public function getAll(array $params, array $with = []): Collection;
    public function show(int $id): Model;
    public function update(int $id, array $validated): Model;
    public function delete(int $id): ?bool;

}
