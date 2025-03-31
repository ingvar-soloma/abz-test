<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{

    public function run(): void
    {
        $positions = ['Lawyer', 'Content manager', 'Security', 'Designer'];

        foreach ($positions as $position) {
            Position::firstOrCreate(['name' => $position]);
        }
    }
}
