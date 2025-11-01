<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class ProductDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $label,
        public readonly string $slug,
        public readonly float $price,
        public readonly string $image,
        public readonly bool $liked = false
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'slug' => $this->slug,
            'price' => $this->price,
            'image' => $this->image,
            'liked' => $this->liked,
        ];
    }
}
