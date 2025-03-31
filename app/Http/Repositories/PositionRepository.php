<?php

namespace App\Http\Repositories;

use App\Models\Positions;

class PositionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Positions());
    }
}
