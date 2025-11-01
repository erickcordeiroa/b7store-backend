<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class CheckoutDTO
{
    public function __construct(
        public readonly ?string $error,
        public readonly ?OrderDTO $order
    ) {
    }

    public function toArray(): array
    {
        if ($this->error) {
            return [
                'error' => $this->error,
                'order' => null,
            ];
        }

        return [
            'error' => null,
            'order' => $this->order->toArray(),
        ];
    }
}

