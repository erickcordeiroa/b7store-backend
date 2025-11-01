<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\OrderRepositoryInterface;
use App\Domain\Entities\Order;
use App\Domain\Entities\OrderProduct;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function save(Order $order): void
    {
        $order->save();
    }

    /**
     * @param array<array{product_id: int, quantity: int, price: float}> $products
     */
    public function attachProducts(Order $order, array $products): void
    {
        $order->products()->createMany($products);
    }
}

