<?php

namespace App\Http\Services;

use App\Http\Repositories\PositionRepository;

class PositionService extends BaseService
{
    public function __construct(readonly protected PositionRepository $repository,)
    {
    }
}
