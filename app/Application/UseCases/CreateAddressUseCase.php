<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\AddressDTO;
use App\Domain\Repositories\AddressRepositoryInterface;

class CreateAddressUseCase
{
    public function __construct(
        private readonly AddressRepositoryInterface $addressRepository
    ) {
    }

    public function execute(array $addressData, int $userId): AddressDTO
    {
        $addressData['user_id'] = $userId;
        $address = $this->addressRepository->create($addressData);

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
    }
}
