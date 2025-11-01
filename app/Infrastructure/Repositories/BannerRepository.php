<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\BannerRepositoryInterface;
use App\Domain\Entities\Banner;
use Illuminate\Support\Collection;

class BannerRepository implements BannerRepositoryInterface
{
    /**
     * @return Collection<int, Banner>
     */
    public function findAll(): Collection
    {
        return Banner::all();
    }
}
