<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\CheckoutCartUseCase;
use App\Application\UseCases\GetCartProductsUseCase;
use App\Application\UseCases\GetShippingUseCase;
use App\Infrastructure\Http\Requests\CheckoutCartRequest;
use App\Infrastructure\Http\Requests\GetShippingRequest;
use App\Infrastructure\Http\Requests\MountCartRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private readonly GetCartProductsUseCase $getCartProductsUseCase,
        private readonly GetShippingUseCase $getShippingUseCase,
        private readonly CheckoutCartUseCase $checkoutCartUseCase
    ) {
    }

    public function mount(MountCartRequest $request): JsonResponse
    {
        $ids = $request->validated('ids');
        $products = $this->getCartProductsUseCase->execute($ids);

        return response()->json([
            'error' => null,
            'products' => $products->map(fn ($product) => $product->toArray())->toArray(),
        ], 200);
    }

    public function shipping(GetShippingRequest $request): JsonResponse
    {
        $shippingDTO = $this->getShippingUseCase->execute($request->validated('zipcode'));

        return response()->json([
            'error' => null,
            'shipping' => $shippingDTO->toArray(),
        ], 200);
    }

    public function checkout(CheckoutCartRequest $request): JsonResponse
    {
        $user = Auth::user();
        $cart = $request->validated('cart');
        $addressId = $request->validated('shipping_address_id');

        $checkoutDTO = $this->checkoutCartUseCase->execute($cart, $addressId, $user->id);

        if ($checkoutDTO->error) {
            return response()->json(
                $checkoutDTO->toArray(),
                400
            );
        }

        return response()->json([
            'error' => null,
            'order' => $checkoutDTO->order->toArray(),
        ], 200);
    }
}
