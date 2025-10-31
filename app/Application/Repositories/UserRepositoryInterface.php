<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function create(array $data): User;

    public function save(User $user): void;
}
