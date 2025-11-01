<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\CartProductDTO;
use App\Application\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class GetCartProductsUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * @param array<int> $productIds
     * @return Collection<int, CartProductDTO>
     */
    public function execute(array $productIds): Collection
    {
        $products = $this->productRepository->findByIds($productIds);

        return $products->map(function ($product) {
            $firstImage = $product->images->first();
            $imageUri = $firstImage
                ? asset('storage/' . $firstImage->uri)
                : asset('storage/products/image-not-found.jpeg');

            return new CartProductDTO(
                id: $product->id,
                label: $product->label,
                price: (float) $product->price,
                image: $imageUri
            );
        });
    }
}
