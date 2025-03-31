<?php

use Database\Seeders\PositionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

describe('GET /api/positions', function () {
    it('returns 404 if no positions found', function () {
        $response = getJson('/api/v1/positions');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Positions not found',
            ]);
    });

    it('returns a list of positions', function () {
        $this->seed(PositionSeeder::class);

        $response = getJson('/api/v1/positions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'positions' => [
                    '*' => ['id', 'name'],
                ],
            ])
            ->assertJsonFragment(['name' => 'Lawyer'])
            ->assertJsonFragment(['name' => 'Content manager'])
            ->assertJsonFragment(['name' => 'Security'])
            ->assertJsonFragment(['name' => 'Designer']);
    });
});
