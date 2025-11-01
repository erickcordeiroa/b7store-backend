<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\AddressRepositoryInterface;
use App\Domain\Entities\Address;
use App\Domain\Entities\User;
use Illuminate\Support\Collection;

class AddressRepository implements AddressRepositoryInterface
{
    /**
     * @return Collection<int, Address>
     */
    public function findByUserId(int $userId): Collection
    {
        $user = User::findOrFail($userId);
        return $user->addresses()->get([
            'id',
            'zipcode',
            'street',
            'number',
            'city',
            'state',
            'country',
            'complement'
        ]);
    }

    public function findByUserIdAndId(int $userId, int $addressId): ?Address
    {
        $user = User::findOrFail($userId);
        return $user->addresses()->where('id', $addressId)->first();
    }

    public function create(array $data): Address
    {
        $user = User::findOrFail($data['user_id']);
        return $user->addresses()->create($data);
    }
}
