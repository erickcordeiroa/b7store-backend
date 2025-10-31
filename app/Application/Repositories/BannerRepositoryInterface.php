<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\Entities\Banner;
use Illuminate\Support\Collection;

interface BannerRepositoryInterface
{
    /**
     * @return Collection<int, Banner>
     */
    public function findAll(): Collection;
}
