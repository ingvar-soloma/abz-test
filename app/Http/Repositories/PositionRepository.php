<?php

namespace App\Http\Repositories;

use App\Models\Position;

class PositionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Position());
    }
}
