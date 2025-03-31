<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RegistrationToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $token = Str::random(80);

        RegistrationToken::create([
            'tokenable_type' => User::class,
            'tokenable_id' => 0,
            'name' => 'registration_token',
            'token' => hash('sha256', $token),
            'abilities' => ['register'],
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
}
