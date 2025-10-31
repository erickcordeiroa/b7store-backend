<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return User::create($data);
    }

    public function save(User $user): void
    {
        $user->save();
    }
}
