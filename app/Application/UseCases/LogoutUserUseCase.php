<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;

class LogoutUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function execute(User $user): void
    {
        $user->revokeAllTokens();
        $this->userRepository->save($user);
    }
}
