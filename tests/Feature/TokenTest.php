<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);


it('successfully generates a registration token', function () {
    $response = getJson('/api/v1/token');

    $response->assertStatus(200)
        ->assertJsonStructure(['success', 'token'])
        ->assertJson(['success' => true]);

    expect($response->json('token'))->not->toBeEmpty();

    $this->assertDatabaseHas('personal_access_tokens', [
        'token' => hash('sha256', $response->json('token')),
    ]);
});
