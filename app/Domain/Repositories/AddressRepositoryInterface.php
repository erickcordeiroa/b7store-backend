<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Address;
use Illuminate\Support\Collection;

interface AddressRepositoryInterface
{
    /**
     * @return Collection<int, Address>
     */
    public function findByUserId(int $userId): Collection;

    public function findByUserIdAndId(int $userId, int $addressId): ?Address;

    public function create(array $data): Address;
}
