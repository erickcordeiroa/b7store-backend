<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\AddressDTO;
use App\Application\Repositories\AddressRepositoryInterface;
use Illuminate\Support\Collection;

class GetUserAddressesUseCase
{
    public function __construct(
        private readonly AddressRepositoryInterface $addressRepository
    ) {
    }

    /**
     * @return Collection<int, AddressDTO>
     */
    public function execute(int $userId): Collection
    {
        $addresses = $this->addressRepository->findByUserId($userId);

        return $addresses->map(function ($address) {
            return new AddressDTO(
                id: $address->id,
                zipcode: $address->zipcode,
                street: $address->street,
                number: $address->number,
                city: $address->city,
                state: $address->state,
                country: $address->country,
                complement: $address->complement
            );
        });
    }
}
