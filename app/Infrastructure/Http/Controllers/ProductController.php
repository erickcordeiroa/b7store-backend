<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\DTOs\ProductFilterDTO;
use App\Application\UseCases\GetProductUseCase;
use App\Application\UseCases\GetProductsUseCase;
use App\Application\UseCases\GetRelatedProductsUseCase;
use App\Infrastructure\Http\Requests\GetProductsRequest;
use App\Infrastructure\Http\Requests\GetRelatedProductsRequest;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly GetProductsUseCase $getProductsUseCase,
        private readonly GetProductUseCase $getProductUseCase,
        private readonly GetRelatedProductsUseCase $getRelatedProductsUseCase
    ) {
    }

    public function index(GetProductsRequest $request): JsonResponse
    {
        $filterDTO = new ProductFilterDTO(
            metadataFilters: $request->getMetadataFilters(),
            orderBy: $request->query('orderBy'),
            limit: (int) $request->query('limit', 15)
        );

        $products = $this->getProductsUseCase->execute($filterDTO);

        return response()->json([
            'error' => null,
            'products' => $products->map(fn ($product) => $product->toArray())->toArray(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $productDTO = $this->getProductUseCase->execute($slug);

        if (!$productDTO) {
            return response()->json([
                'error' => 'Produto não encontrado',
                'product' => null,
                'category' => null,
            ], 404);
        }

        return response()->json([
            'error' => null,
            ...$productDTO->toArray(),
        ]);
    }

    public function related(GetRelatedProductsRequest $request, string $slug): JsonResponse
    {
        $limit = (int) $request->query('limit', 10);
        $products = $this->getRelatedProductsUseCase->execute($slug, $limit);

        if ($products->isEmpty()) {
            return response()->json([
                'error' => 'Produto não encontrado',
                'products' => [],
            ], 404);
        }

        return response()->json([
            'error' => null,
            'products' => $products->map(fn ($product) => $product->toArray())->toArray(),
        ]);
    }
}
