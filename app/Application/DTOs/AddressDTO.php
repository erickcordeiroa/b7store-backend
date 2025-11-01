<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class AddressDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $zipcode,
        public readonly string $street,
        public readonly string $number,
        public readonly string $city,
        public readonly string $state,
        public readonly string $country,
        public readonly ?string $complement = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'zipcode' => $this->zipcode,
            'street' => $this->street,
            'number' => $this->number,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'complement' => $this->complement,
        ];
    }
}
