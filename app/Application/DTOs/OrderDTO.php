<?php

declare(strict_types=1);

namespace App\Application\DTOs;

use Illuminate\Support\Collection;

class OrderDTO
{
    /**
     * @param Collection<int, OrderProductDTO> $products
     */
    public function __construct(
        public readonly int $id,
        public readonly string $trackingCode,
        public readonly float $totalAmount,
        public readonly string $status,
        public readonly float $shippingCost,
        public readonly int $shippingDays,
        public readonly AddressDTO $shippingAddress,
        public readonly Collection $products
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'tracking_code' => $this->trackingCode,
            'total_amount' => $this->totalAmount,
            'status' => $this->status,
            'shipping_cost' => $this->shippingCost,
            'shipping_days' => $this->shippingDays,
            'shipping_address' => $this->shippingAddress->toArray(),
            'products' => $this->products->map(fn ($product) => $product->toArray())->toArray(),
        ];
    }
}

