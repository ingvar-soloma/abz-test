<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('fails to register a user with missing required fields', function () {
    $response = postJson('/api/v1/users', [
        'name' => 'Alice',
        // Missing email, phone, position_id, and photo
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Validation failed',
        ]);
});

it('fails to register a user with invalid email', function () {
    $response = postJson('/api/v1/users', [
        'name' => 'Alice',
        'email' => 'invalid-email',
        'phone' => '380500740599',
        'position_id' => 1,
        'photo' => UploadedFile::fake()->image('photo.jpg', 100, 100)->size(1000),
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Validation failed',
        ]);
});

it('fails to register a user with existing email or phone', function () {
    // Assuming a user with this email or phone already exists
    $response = postJson('/api/v1/users', [
        'name' => 'Alice',
        'email' => 'existing@mail.com',
        'phone' => '380500740599',
        'position_id' => 1,
        'photo' => UploadedFile::fake()->image('photo.jpg', 100, 100)->size(1000),
    ]);

    $response->assertStatus(409)
        ->assertJson([
            'success' => false,
            'message' => 'User with this phone or email already exist',
        ]);
});

it('retrieves a list of users', function () {
    $response = getJson('/api/v1/users');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'page',
            'total_pages',
            'total_users',
            'count',
            'links',
            'users' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'position_id',
                    'position',
                    'photo',
                ],
            ],
        ]);
});

it('returns 404 if the page does not exist', function () {
    $response = $this->getJson('/api/v1/users?page=9999');

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Page not found',
        ]);
});

it('retrieves a user by id', function () {
    $userId = 1; // Assuming a user with this ID exists

    $response = getJson("/api/v1/users/{$userId}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'user' => [
                'id',
                'name',
                'email',
                'phone',
                'position_id',
                'position',
                'photo',
            ],
        ]);
});

it('fails to retrieve a user with non-existent id', function () {
    $userId = 9999; // Assuming no user with this ID exists

    $response = getJson("/api/v1/users/{$userId}");

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'User not found',
        ]);
});

it('registers a new user successfully', function () {
    Storage::fake('photos');

    $response = $this->postJson('/api/v1/users', [
        'name' => 'Alice',
        'email' => 'alice.fonk@mail.com',
        'phone' => '380500740599',
        'position_id' => 1,
        'photo' => UploadedFile::fake()->image('photo.jpg', 100, 100)->size(5000),
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'New user successfully registered',
        ]);
});

it('validates user name length', function () {
    $shortNameResponse = $this->postJson('/api/users', [
        'name' => 'A',
        'email' => 'alice.fonk@mail.com',
        'phone' => '+380500740599',
        'position_id' => 1,
        'photo' => UploadedFile::fake()->image('avatar.jpg', 100, 100),
    ]);

    $shortNameResponse->assertStatus(422)
        ->assertJsonValidationErrors(['name']);

    $longNameResponse = $this->postJson('/api/users', [
        'name' => str_repeat('A', 61),
        'email' => 'alice.fonk@mail.com',
        'phone' => '+380500740599',
        'position_id' => 1,
        'photo' => UploadedFile::fake()->image('avatar.jpg', 100, 100),
    ]);

    $longNameResponse->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('requires a phone number starting with +380', function () {
    $response = $this->postJson('/api/users', [
        'name' => 'Alice',
        'email' => 'alice.fonk@mail.com',
        'phone' => '1234567890',
        'position_id' => 1,
        'photo' => UploadedFile::fake()->image('avatar.jpg', 100, 100),
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['phone']);
});

it('requires a valid position_id', function () {
    $response = $this->postJson('/api/users', [
        'name' => 'Alice',
        'email' => 'alice.fonk@mail.com',
        'phone' => '+380500740599',
        'position_id' => 'invalid',
        'photo' => UploadedFile::fake()->image('avatar.jpg', 100, 100),
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['position_id']);
});

it('validates photo constraints', function () {
    $largePhoto = UploadedFile::fake()->image('avatar.jpg')->size(6000);

    $response = $this->postJson('/api/users', [
        'name' => 'Alice',
        'email' => 'alice.fonk@mail.com',
        'phone' => '+380500740599',
        'position_id' => 1,
        'photo' => $largePhoto,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['photo']);
});

it('returns 401 if the token is missing', function () {
    $response = $this->postJson('/api/users', [
        'name' => 'Alice',
        'email' => 'alice.fonk@mail.com',
        'phone' => '+380500740599',
        'position_id' => 1,
        'photo' => UploadedFile::fake()->image('avatar.jpg', 100, 100),
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'The token expired.',
        ]);
});

it('returns 401 if the token is expired', function () {
    $expiredToken = 'expired_token_example';

    $response = $this->postJson('/api/users', [
        'name' => 'Alice',
        'email' => 'alice.fonk@mail.com',
        'phone' => '+380500740599',
        'position_id' => 1,
        'photo' => UploadedFile::fake()->image('avatar.jpg', 100, 100),
    ], [
        'Token' => $expiredToken
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'The token expired.',
        ]);
});
