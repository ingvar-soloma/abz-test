<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

dataset('invalid users', [
    'missing required fields' => [[]],
    'invalid email' => [['email' => 'invalid-email']],
    'existing email' => [['email' => 'existing@mail.com', 'phone' => '380500740599']],
    'invalid phone' => [['phone' => '1234567890']],
    'invalid position_id' => [['position_id' => 'invalid']],
    'large photo' => [['photo' => UploadedFile::fake()->image('photo.jpg', 100, 100)->size(6000)]],
]);

dataset('tokens', [
    'missing token' => [null],
    'expired token' => ['expired_token_example'],
]);

function postUser(array $overrides = [], ?string $token = 'valid_token'): TestResponse
{
    $defaultData = [
        'name' => 'Alice',
        'email' => 'alice.fonk@mail.com',
        'phone' => '+380500740599',
        'position_id' => 1,
        'photo' => UploadedFile::fake()->image('photo.jpg', 100, 100),
    ];

    return postJson('/api/v1/users', array_merge($defaultData, $overrides), [
        'Token' => $token,
    ]);
}


it('fails to register a user with invalid data')
    ->with('invalid_users')
    ->tap(fn ($data) => postUser($data))
    ->assertStatus(422)
    ->assertJson(['success' => false, 'message' => 'Validation failed']);

it('registers a new user successfully', function () {
    Storage::fake('photos');

    postUser()
        ->assertStatus(201)
        ->assertJson(['success' => true, 'message' => 'New user successfully registered']);
});

it('fails to register a user with existing email or phone', function () {
    postUser(['email' => 'existing@mail.com'])
        ->assertStatus(409)
        ->assertJson(['success' => false, 'message' => 'User with this phone or email already exist']);
});

it('retrieves a list of users', function () {
    getJson('/api/v1/users')
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'page',
            'total_pages',
            'total_users',
            'count',
            'links',
            'users' => [['id', 'name', 'email', 'phone', 'position_id', 'position', 'photo']],
        ]);
});

it('returns 404 if the page does not exist', function () {
    getJson('/api/v1/users?page=9999')
        ->assertStatus(404)
        ->assertJson(['success' => false, 'message' => 'Page not found']);
});

it('retrieves a user by id', function () {
    getJson('/api/v1/users/1')
        ->assertStatus(200)
        ->assertJsonStructure(['success', 'user' => ['id', 'name', 'email', 'phone', 'position_id', 'position', 'photo']]);
});

it('fails to retrieve a user with non-existent id', function () {
    getJson('/api/v1/users/9999')
        ->assertStatus(404)
        ->assertJson(['success' => false, 'message' => 'User not found']);
});

it('returns 401 if the token is invalid')
    ->with('tokens')
    ->tap(fn ($token) => postUser([], $token))
    ->assertStatus(401)
    ->assertJson(['success' => false, 'message' => 'The token expired']);
