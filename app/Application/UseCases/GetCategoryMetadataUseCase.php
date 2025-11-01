<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\CategoryDTO;
use App\Application\DTOs\CategoryMetadataDTO;
use App\Application\DTOs\MetadataValueDTO;
use App\Application\Repositories\CategoryRepositoryInterface;

class GetCategoryMetadataUseCase
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
    }

    /**
     * @return array{category: CategoryDTO|null, metadata: array<int, CategoryMetadataDTO>}
     */
    public function execute(string $slug): array
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if (!$category) {
            return [
                'category' => null,
                'metadata' => [],
            ];
        }

        $categoryDTO = new CategoryDTO(
            id: $category->id,
            name: $category->name,
            slug: $category->slug
        );

        $metadataDTOs = $category->metadata->map(function ($metadata) {
            $values = $metadata->values->map(function ($value) {
                return new MetadataValueDTO(
                    id: (int) $value->id,
                    label: $value->label
                );
            })->toArray();

            return new CategoryMetadataDTO(
                id: (int) $metadata->id,
                label: $metadata->name,
                values: $values
            );
        })->toArray();

        return [
            'category' => $categoryDTO,
            'metadata' => $metadataDTOs,
        ];
    }
}
