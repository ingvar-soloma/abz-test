<?php

use function Pest\Laravel\getJson;

it('successfully generates a registration token', function () {
    $response = getJson('/api/token');

    $response->assertStatus(200)
        ->assertJsonStructure(['success', 'token'])
        ->assertJson(['success' => true]);

    expect($response->json('token'))->not->toBeEmpty();

    $this->assertDatabaseHas('registration_tokens', [
        'token' => hash('sha256', $response->json('token')),
    ]);
});
