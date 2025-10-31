<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\CategoryRepositoryInterface;
use App\Domain\Entities\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->with('metadata.values')->first();
    }
}
