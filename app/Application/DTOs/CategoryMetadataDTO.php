<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class CategoryMetadataDTO
{
    /**
     * @param array<int, MetadataValueDTO> $values
     */
    public function __construct(
        public readonly int $id,
        public readonly string $label,
        public readonly array $values
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'values' => array_map(fn ($value) => $value->toArray(), $this->values),
        ];
    }
}
