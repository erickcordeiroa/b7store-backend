<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\ProductDTO;
use App\Application\DTOs\ProductFilterDTO;
use App\Application\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class GetProductsUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * @return Collection<int, ProductDTO>
     */
    public function execute(ProductFilterDTO $filterDTO): Collection
    {
        $products = $this->productRepository->findByFilters(
            metadataFilters: $filterDTO->metadataFilters,
            orderBy: $filterDTO->getOrderByColumn(),
            limit: $filterDTO->limit
        );

        return $products->map(function ($product) {
            $firstImage = $product->images->first();
            $imageUri = $firstImage ? asset('storage/' . $firstImage->uri) : asset('storage/products/default.png');

            return new ProductDTO(
                id: $product->id,
                label: $product->label,
                slug: $product->slug,
                price: (float) $product->price,
                image: $imageUri,
                liked: false // TODO: Implementar o Liked
            );
        });
    }
}
