<?php

namespace App\TravelService\Infrastructure\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class ForceJwtAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'message' => 'Unauthenticated user'
                ], 401);
            }

            Auth::setUser($user);

        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Invalid or missing JWT token',
                'error' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
