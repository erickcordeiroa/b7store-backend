<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\Entities\Order;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function create(array $data): Order;

    public function save(Order $order): void;

    /**
     * @param array<array{product_id: int, quantity: int, price: float}> $products
     */
    public function attachProducts(Order $order, array $products): void;
}

