<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new User());
    }
}
