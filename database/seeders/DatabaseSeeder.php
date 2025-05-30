<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    final public function run(): void
    {
        $this->call(PositionSeeder::class);
        $this->call(UserSeeder::class);

    }
}
