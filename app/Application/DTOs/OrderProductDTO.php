<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class OrderProductDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
        public readonly float $price
    ) {
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}

