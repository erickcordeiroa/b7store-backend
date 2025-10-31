<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\UserDTO;
use App\Application\Repositories\UserRepositoryInterface;

class RegisterUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function execute(array $userData): UserDTO
    {
        $user = $this->userRepository->create($userData);

        return new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );
    }
}
