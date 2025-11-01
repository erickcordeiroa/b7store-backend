<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\GetBannersUseCase;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    public function __construct(
        private readonly GetBannersUseCase $getBannersUseCase
    ) {
    }

    public function index(): JsonResponse
    {
        $banners = $this->getBannersUseCase->execute();

        return response()->json([
            'error' => null,
            'banners' => $banners->map(fn ($banner) => $banner->toArray())->toArray(),
        ]);
    }
}
