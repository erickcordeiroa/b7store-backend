<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\AddressDTO;
use App\Application\DTOs\CheckoutDTO;
use App\Application\DTOs\OrderDTO;
use App\Application\DTOs\OrderProductDTO;
use App\Application\Repositories\AddressRepositoryInterface;
use App\Application\Repositories\OrderRepositoryInterface;
use App\Application\Repositories\ProductRepositoryInterface;
use App\Domain\Entities\Order;
use Illuminate\Support\Collection;

class CheckoutCartUseCase
{
    private const SHIPPING_COST = 20.00;
    private const SHIPPING_DAYS = 7;

    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly AddressRepositoryInterface $addressRepository,
        private readonly OrderRepositoryInterface $orderRepository
    ) {
    }

    /**
     * @param array<array{product_id: int, quantity: int}> $cart
     */
    public function execute(array $cart, int $addressId, int $userId): CheckoutDTO
    {
        // Validation of shipping address belongs to user
        $address = $this->addressRepository->findByUserIdAndId($userId, $addressId);
        if (!$address) {
            return new CheckoutDTO(
                error: 'Endereço de entrega não pertence ao usuário logado.',
                order: null
            );
        }

        // Get all products exists in cart
        $productIds = array_column($cart, 'product_id');
        $products = $this->productRepository->findByIds($productIds);

        if ($products->count() !== count($productIds)) {
            return new CheckoutDTO(
                error: 'Um ou mais produtos não foram encontrados.',
                order: null
            );
        }

        // Calculate total price of cart
        $total = $this->calculateTotal($cart, $products);

        // Create order
        $orderData = $this->prepareOrderData($userId, $total, $address);
        $order = $this->orderRepository->create($orderData);

        // Attach products to order
        $orderProducts = $this->prepareOrderProducts($cart, $products);
        $this->orderRepository->attachProducts($order, $orderProducts);

        // Load products relation
        $order->load('products');

        // Create DTOs
        $orderDTO = $this->buildOrderDTO($order, $address);

        return new CheckoutDTO(
            error: null,
            order: $orderDTO
        );
    }

    /**
     * @param array<array{product_id: int, quantity: int}> $cart
     * @param Collection<int, \App\Domain\Entities\Product> $products
     */
    private function calculateTotal(array $cart, Collection $products): float
    {
        $total = 0.0;

        foreach ($cart as $cartItem) {
            $product = $products->where('id', $cartItem['product_id'])->first();
            if ($product) {
                $total += (float) $product->price * $cartItem['quantity'];
            }
        }

        return $total;
    }

    /**
     * @return array<string, mixed>
     */
    private function prepareOrderData(int $userId, float $total, \App\Domain\Entities\Address $address): array
    {
        $order = new Order();
        $trackingCode = $order->generateTrackingCode();

        return [
            'user_id' => $userId,
            'tracking_code' => $trackingCode,
            'total_amount' => $total,
            'status' => 'pending',
            'shipping_cost' => self::SHIPPING_COST,
            'shipping_days' => self::SHIPPING_DAYS,
            'shipping_zipcode' => $address->zipcode,
            'shipping_street' => $address->street,
            'shipping_number' => $address->number,
            'shipping_complement' => $address->complement,
            'shipping_city' => $address->city,
            'shipping_state' => $address->state,
            'shipping_country' => $address->country,
        ];
    }

    /**
     * @param array<array{product_id: int, quantity: int}> $cart
     * @param Collection<int, \App\Domain\Entities\Product> $products
     * @return array<array{product_id: int, quantity: int, price: float}>
     */
    private function prepareOrderProducts(array $cart, Collection $products): array
    {
        return array_map(function ($cartItem) use ($products) {
            $product = $products->where('id', $cartItem['product_id'])->first();
            return [
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity'],
                'price' => $product ? (float) $product->price : 0.0,
            ];
        }, $cart);
    }

    private function buildOrderDTO(Order $order, \App\Domain\Entities\Address $address): OrderDTO
    {
        $addressDTO = new AddressDTO(
            id: $address->id,
            zipcode: $address->zipcode,
            street: $address->street,
            number: $address->number,
            city: $address->city,
            state: $address->state,
            country: $address->country,
            complement: $address->complement
        );

        $productsDTO = $order->products->map(function ($orderProduct) {
            return new OrderProductDTO(
                productId: $orderProduct->product_id,
                quantity: $orderProduct->quantity,
                price: (float) $orderProduct->price
            );
        });

        return new OrderDTO(
            id: $order->id,
            trackingCode: $order->tracking_code,
            totalAmount: (float) $order->total_amount,
            status: $order->status,
            shippingCost: (float) $order->shipping_cost,
            shippingDays: $order->shipping_days,
            shippingAddress: $addressDTO,
            products: $productsDTO
        );
    }
}