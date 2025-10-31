<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class ProductFilterDTO
{
    /**
     * @param array<string, int> $metadataFilters
     */
    public function __construct(
        public readonly array $metadataFilters = [],
        public readonly ?string $orderBy = null,
        public readonly int $limit = 15
    ) {
    }

    public function getOrderByColumn(): ?string
    {
        return match ($this->orderBy) {
            'views' => 'views_count',
            'selling' => 'sales_count',
            'price' => 'price',
            default => null,
        };
    }
}
