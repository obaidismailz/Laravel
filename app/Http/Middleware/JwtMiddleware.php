<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {

            return response()->json([
                'response' => [
                    'message' => 'User not logged in & Token not found', $e->getMessage(),
                    'status' => 401,
                ],
            ],401);
        }
        return $next($request);
    }
}
