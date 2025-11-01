<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class ShippingDTO
{
    public function __construct(
        public readonly string $zipcode,
        public readonly float $price,
        public readonly int $days
    ) {
    }

    public function toArray(): array
    {
        return [
            'zipcode' => $this->zipcode,
            'price' => $this->price,
            'days' => $this->days,
        ];
    }
}
