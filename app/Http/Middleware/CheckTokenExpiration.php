<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class CheckTokenExpiration
{
    const THE_TOKEN_EXPIRED = 'The token expired';

    final public function handle(Request $request, Closure $next): mixed
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['success' => false, 'message' => self::THE_TOKEN_EXPIRED], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || Carbon::parse($accessToken->created_at)->addMinutes(40)->isPast()) {
            return response()->json(['success' => false, 'message' => self::THE_TOKEN_EXPIRED], 401);
        }

        $accessToken->delete();

        return $next($request);
    }
}
