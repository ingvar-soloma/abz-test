<?php

namespace Database\Seeders;

use App\Models\Positions;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{

    final public function run(): void
    {
        Positions::insert([
            ['name' => 'Lawyer'],
            ['name' => 'Content manager'],
            ['name' => 'Security'],
            ['name' => 'Designer'],
        ]);
    }
}
