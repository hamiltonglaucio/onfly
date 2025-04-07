<?php

namespace App\AuthService\UserInterface\Controllers;

use App\AuthService\UserInterface\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\AuthService\UserInterface\Requests\RegisterRequest;
use App\AuthService\UseCases\RegisterUser;
use App\AuthService\UseCases\DTO\RegisterUserDTO;
use App\AuthService\Domain\ValueObjects\Email;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Registrar novo usuário
     *
     * Registra um novo usuário na aplicação.
     *
     * @group Autenticação
     *
     * @bodyParam name string required Nome completo do usuário. Example: João Silva
     * @bodyParam email string required E-mail válido e único. Example: joao@email.com
     * @bodyParam password string required Senha de acesso. Example: segredo123
     *
     * @response 201 {
     *   "message": "Usuário registrado com sucesso!",
     *   "id": 1,
     *   "name": "João Silva",
     *   "email": "joao2@email.com"
     * }
     *
     * @response 422 {
     *   "message": "Validation failed.",
     *   "errors": {
     *     "email": [
     *       "The email has already been taken."
     *     ]
     *   }
     * }
     */
    public function register(RegisterRequest $request, RegisterUser $useCase) : JsonResponse
    {
        $dto = new RegisterUserDTO(
            $request->input('name'),
            new Email($request->input('email')),
            $request->input('password')
        );

        $user = $useCase->handle($dto);

        return response()->json([
            'message' => 'Usuário registrado com sucesso!',
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => (string) $user->getEmail()
        ], 201);
    }

    /**
     * Login do usuário
     *
     * Realiza o login de um usuário com e-mail e senha. Retorna um token JWT válido para autenticação.
     *
     * @group Autenticação
     *
     * @bodyParam email string required E-mail do usuário. Example: joao@email.com
     * @bodyParam password string required Senha do usuário. Example: segredo123
     *
     * @response 200 {
     *   "message": "Authenticated!",
     *   "token": "--bearer-token--"
     * }
     *
     * @response 401 {
     *   "error": "Invalid credentials"
     * }
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return ([
            'message' => 'Authenticated!',
            'token' => $token
        ]);
    }

    /**
     * Obter dados do usuário autenticado
     *
     * Retorna as informações do usuário atualmente autenticado com base no token JWT.
     *
     * @authenticated
     * @group Autenticação
     *
     * @response 200 {
     *   "message": "Authenticated!",
     *   "data": {
     *     "id": 3,
     *     "name": "João Silva",
     *     "email": "joao@email.com"
     *   }
     * }
     *
     * @response 401 {
     *   "message": "Attempt to read property \"id\" on null",
     *   "exception": "ErrorException"
     * }
     */
    public function me(): JsonResponse
    {
        $user = Auth::guard('api')->user();

        return response()->json([
            'message' => 'Authenticated!',
            'data' => [
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email
            ]
        ]);
    }

    /**
     * Logout do usuário
     *
     * Invalida o token JWT atual, encerrando a sessão do usuário autenticado.
     *
     * @authenticated
     * @group Autenticação
     *
     * @response 200 {
     *   "message": "Successfully logged out"
     * }
     */

    public function logout() : JsonResponse
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Renovar token JWT
     *
     * Gera um novo token JWT com base no token atual válido.
     *
     * @authenticated
     * @group Autenticação
     *
     * @response 200 {
     *   "message": "Successfully refreshed!",
     *   "token": "--bearer-token"
     * }
     *
     * @response 401 {
     *   "message": "The token has been blacklisted",
     *   "exception": "Tymon\\JWTAuth\\Exceptions\\TokenBlacklistedException"
     * }
     */
    public function refresh(): JsonResponse
    {
        $token = auth('api')->refresh();
        return response()->json([
            'message' => 'Successfully refreshed!',
            'token' => $token
        ]);
    }
}
