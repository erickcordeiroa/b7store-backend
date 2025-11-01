<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\BannerDTO;
use App\Domain\Repositories\BannerRepositoryInterface;
use Illuminate\Support\Collection;

class GetBannersUseCase
{
    public function __construct(
        private readonly BannerRepositoryInterface $bannerRepository
    ) {
    }

    /**
     * @return Collection<int, BannerDTO>
     */
    public function execute(): Collection
    {
        $banners = $this->bannerRepository->findAll();

        return $banners->map(function ($banner) {
            return new BannerDTO(
                uri: asset('storage/' . $banner->uri),
                link: $banner->link
            );
        });
    }
}
