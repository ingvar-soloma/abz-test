<?php

use App\Models\User;
use Database\Seeders\PositionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

dataset('invalid users', [
    'missing required fields' => [['email' => null, 'phone' => null, 'position_id' => null]],
    'invalid name' => [['name' => '']],
    'short password' => [['password' => '123']],
    'short name' => [['name' => 'A']],
    'long name' => [['name' => str_repeat('A', 256)]],
    'invalid email' => [['email' => 'invalid-email']],
    'invalid phone' => [['phone' => '1234567890']],
    'invalid position_id' => [['position_id' => 'invalid']],
    'large photo' => [['photo' => UploadedFile::fake()
        ->image('photo.jpg', 100, 100)->size(6000)]],
]);

dataset('tokens', [
    'missing token' => [''],
    'expired token' => ['expired_token_example'],
]);



function postUser(array $overrides = [], string $token = 'valid_token'): TestResponse
{
    $defaultData = [
        'name' => 'Alice',
        'email' => 'alice.fonk@mail.com',
        'password' => 'password123',
        'phone' => '+380500740599',
        'position_id' => 1,
        'photo' => UploadedFile::fake()->image('photo.jpg', 100, 100),
    ];

    return postJson('/api/v1/users', array_merge($defaultData, $overrides), [
        'Authorization' => 'Bearer ' . $token,
    ]);
}


// group tests with getting token before each test
describe('Token management', function () {

    beforeEach(function () {
        $this->token = getJson('/api/v1/token')->json('token');
        $this->seed(PositionSeeder::class);

    });


    it('fails to register a user with invalid data', function (array $data) {

        postUser($data, $this->token)
            ->assertStatus(422)
            ->assertJson(['success' => false, 'message' => 'Validation failed']);
    })->with('invalid users');

    it('retrieves a valid token', function () {
        expect($this->token)->toBeString()->not->toBeEmpty();
    });


        it('registers a new user successfully', function () {
            Storage::fake('photos');

            assertDatabaseCount('positions', 4);

            postUser([], $this->token)
                ->assertStatus(201)
                ->assertJson(['success' => true, 'message' => 'New user successfully registered']);
        });


        it('fails to register a user with existing email or phone', function () {
            // Create a user with the same email before the test

            $existingEmail = 'existing@mail.com';
            $existingPhone = '+380500740599';
            User::factory()->create([
                'email' => $existingEmail,
                'phone' => $existingPhone,
            ]);

            postUser(['email' => $existingEmail], $this->token)
                ->assertStatus(409)
                ->assertJson(['success' => false, 'message' => 'User with this phone or email already exist']);

            postUser(['phone' => $existingPhone], $this->token)
                ->assertStatus(409)
                ->assertJson(['success' => false, 'message' => 'User with this phone or email already exist']);
        });
});

it('retrieves a list of users', function () {
    $this->seed(PositionSeeder::class);
    $this->seed(UserSeeder::class);

    assertDatabaseCount('positions', 4);
    assertDatabaseCount('users', 45);

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
    $this->seed(PositionSeeder::class);
    $this->seed(UserSeeder::class);

    getJson('/api/v1/users/1')
        ->assertStatus(200)
        ->assertJsonStructure(['success', 'user' => ['id', 'name', 'email', 'phone', 'position_id', 'position', 'photo']]);
});

it('fails to retrieve a user with non-existent id', function () {
    getJson('/api/v1/users/9999')
        ->assertStatus(404)
        ->assertJson(['success' => false, 'message' => 'User not found']);
});

it('returns 401 if the token is invalid', function (string $token) {
    postUser([], $token)
        ->assertStatus(401)
        ->assertJson(['success' => false, 'message' => 'The token expired']);
})->with('tokens');


