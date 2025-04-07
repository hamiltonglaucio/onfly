<?php

namespace App\AuthService\Infrastructure\Middleware;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiAuthenticate extends Authenticate
{
    protected function unauthenticated($request, array $guards): JsonResponse
    {
        return response()->json([
            'message' => 'Não autenticado. Token JWT ausente ou inválido.'
        ], 401);
    }

    protected function redirectTo(Request $request): ?string
    {
        return null;
    }
}
