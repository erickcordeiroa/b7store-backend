<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\LoginUserUseCase;
use App\Application\UseCases\LogoutUserUseCase;
use App\Application\UseCases\RegisterUserUseCase;
use App\Infrastructure\Http\Requests\LoginUserRequest;
use App\Infrastructure\Http\Requests\RegisterUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private readonly RegisterUserUseCase $registerUserUseCase,
        private readonly LoginUserUseCase $loginUserUseCase,
        private readonly LogoutUserUseCase $logoutUserUseCase
    ) {
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $userDTO = $this->registerUserUseCase->execute($request->validated());

        return response()->json([
            'error' => null,
            'user' => $userDTO->toArray(),
        ], 201);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $token = $this->loginUserUseCase->execute(
            $validated['email'],
            $validated['password']
        );

        if (!$token) {
            return response()->json([
                'error' => 'Usuário ou senha inválidos.',
                'token' => null
            ], 401);
        }

        return response()->json([
            'error' => null,
            'token' => $token,
        ], 200);
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $this->logoutUserUseCase->execute($user);

        return response()->json([
            'error' => null,
            'message' => 'Logout realizado com sucesso.'
        ], 200);
    }
}
