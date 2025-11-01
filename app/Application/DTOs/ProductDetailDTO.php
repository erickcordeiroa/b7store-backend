<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class ProductDetailDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $categoryId,
        public readonly string $label,
        public readonly ?string $description,
        public readonly float $price,
        public readonly array $images,
        public readonly CategoryDTO $category
    ) {
    }

    public function toArray(): array
    {
        return [
            'product' => [
                'id' => $this->id,
                'category_id' => $this->categoryId,
                'label' => $this->label,
                'description' => $this->description,
                'price' => $this->price,
                'images' => $this->images,
            ],
            'category' => $this->category->toArray(),
        ];
    }
}
