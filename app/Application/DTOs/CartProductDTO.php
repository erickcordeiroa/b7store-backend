<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class CartProductDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $label,
        public readonly float $price,
        public readonly string $image
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'price' => $this->price,
            'images' => $this->image,
        ];
    }
}
