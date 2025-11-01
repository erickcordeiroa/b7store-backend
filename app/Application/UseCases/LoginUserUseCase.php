<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class LoginUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function execute(string $email, string $password): ?string
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        $user->revokeAllTokens();
        $token = $user->createAuthToken();

        $this->userRepository->save($user);

        return $token;
    }
}
