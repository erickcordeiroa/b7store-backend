<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\Entities\Category;

interface CategoryRepositoryInterface
{
    public function findBySlug(string $slug): ?Category;
}
