<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\ProductRepositoryInterface;
use App\Domain\Entities\Product;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function findBySlug(string $slug): ?Product
    {
        return Product::with(['images', 'category'])->where('slug', $slug)->first();
    }

    /**
     * @param array<string, int> $metadataFilters
     * @return Collection<int, Product>
     */
    public function findByFilters(
        array $metadataFilters = [],
        ?string $orderBy = null,
        int $limit = 15
    ): Collection {
        $query = Product::query()->with('metadata');

        foreach ($metadataFilters as $key => $value) {
            $query->whereHas('metadata', function ($q) use ($key, $value) {
                $q->where('category_metadata_id', $key)->where('metadata_value_id', $value);
            });
        }

        if ($orderBy) {
            $query->orderBy($orderBy, 'desc');
        }

        return $query->with('images')->limit($limit)->get();
    }

    /**
     * @param array<int> $ids
     * @return Collection<int, Product>
     */
    public function findByIds(array $ids): Collection
    {
        return Product::with('images')->whereIn('id', $ids)->get();
    }

    /**
     * @return Collection<int, Product>
     */
    public function findRelatedByCategory(int $categoryId, int $excludeId, int $limit): Collection
    {
        return Product::where('category_id', $categoryId)
            ->where('id', '!=', $excludeId)
            ->with('images')
            ->limit($limit)
            ->get();
    }

    public function save(Product $product): void
    {
        $product->save();
    }
}
