<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class PositionFactory extends Factory
{
    final public function definition(): array
    {
        return [
            'name' => fake()->jobTitle(),
        ];
    }
}
