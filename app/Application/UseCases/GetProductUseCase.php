<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\CategoryDTO;
use App\Application\DTOs\ProductDetailDTO;
use App\Domain\Repositories\ProductRepositoryInterface;

class GetProductUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    public function execute(string $slug): ?ProductDetailDTO
    {
        $product = $this->productRepository->findBySlug($slug);

        if (!$product) {
            return null;
        }

        $product->incrementViews();
        $this->productRepository->save($product);

        $images = ['uri' => asset('storage/products/image-not-found.jpeg')];
        if (!$product->images->isEmpty()) {
            $images = $product->images->map(function ($image) {
                return asset('storage/' . $image->uri);
            })->toArray();
        }

        $categoryDTO = new CategoryDTO(
            id: $product->category->id,
            name: $product->category->name,
            slug: $product->category->slug
        );

        return new ProductDetailDTO(
            id: $product->id,
            categoryId: $product->category_id,
            label: $product->label,
            description: $product->description,
            price: (float) $product->price,
            images: $images,
            category: $categoryDTO
        );
    }
}
