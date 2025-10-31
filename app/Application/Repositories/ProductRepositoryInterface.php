<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\Entities\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function findBySlug(string $slug): ?Product;

    /**
     * @param array<string, int> $metadataFilters
     * @return Collection<int, Product>
     */
    public function findByFilters(
        array $metadataFilters = [],
        ?string $orderBy = null,
        int $limit = 15
    ): Collection;

    /**
     * @param array<int> $ids
     * @return Collection<int, Product>
     */
    public function findByIds(array $ids): Collection;

    /**
     * @return Collection<int, Product>
     */
    public function findRelatedByCategory(int $categoryId, int $excludeId, int $limit): Collection;

    public function save(Product $product): void;
}
