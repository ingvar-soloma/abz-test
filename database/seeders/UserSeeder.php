<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    final public function run(): void
    {
        User::factory()->count(45)->create();
    }
}
