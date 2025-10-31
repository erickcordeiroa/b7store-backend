<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\ProductDTO;
use App\Application\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class GetRelatedProductsUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * @return Collection<int, ProductDTO>
     */
    public function execute(string $slug, int $limit = 10): Collection
    {
        $product = $this->productRepository->findBySlug($slug);

        if (!$product) {
            return collect();
        }

        $relatedProducts = $this->productRepository->findRelatedByCategory(
            categoryId: $product->category_id,
            excludeId: $product->id,
            limit: $limit
        );

        return $relatedProducts->map(function ($relatedProduct) {
            $firstImage = $relatedProduct->images->first();
            $imageUri = $firstImage
                ? asset('storage/' . $firstImage->uri)
                : asset('storage/products/image-not-found.jpeg');

            return new ProductDTO(
                id: $relatedProduct->id,
                label: $relatedProduct->label,
                slug: $relatedProduct->slug,
                price: (float) $relatedProduct->price,
                image: $imageUri,
                liked: false // TODO: Implementar o Liked
            );
        });
    }
}
