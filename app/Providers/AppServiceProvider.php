<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Repositories\AddressRepositoryInterface;
use App\Domain\Repositories\BannerRepositoryInterface;
use App\Domain\Repositories\CategoryRepositoryInterface;
use App\Domain\Repositories\OrderRepositoryInterface;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Repositories\AddressRepository;
use App\Infrastructure\Repositories\BannerRepository;
use App\Infrastructure\Repositories\CategoryRepository;
use App\Infrastructure\Repositories\OrderRepository;
use App\Infrastructure\Repositories\ProductRepository;
use App\Infrastructure\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BannerRepositoryInterface::class, BannerRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Map morph relations for compatibility with old Models namespace
        // This allows Sanctum to resolve both old and new class names
        Relation::morphMap([
            'App\Models\User' => \App\Domain\Entities\User::class,
        ]);
    }
}