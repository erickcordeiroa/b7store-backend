<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\GetCategoryMetadataUseCase;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(
        private readonly GetCategoryMetadataUseCase $getCategoryMetadataUseCase
    ) {
    }

    public function metadata(string $slug): JsonResponse
    {
        if (!is_string($slug) || empty($slug)) {
            return response()->json([
                'error' => 'Slug inválido',
                'category' => null,
                'metadata' => []
            ], 400);
        }

        $result = $this->getCategoryMetadataUseCase->execute($slug);

        if ($result['category'] === null) {
            return response()->json([
                'error' => 'Categoria não encontrada',
                'metadata' => []
            ], 404);
        }

        return response()->json([
            'error' => null,
            'category' => $result['category']->toArray(),
            'metadata' => array_map(fn ($metadata) => $metadata->toArray(), $result['metadata']),
        ]);
    }
}
