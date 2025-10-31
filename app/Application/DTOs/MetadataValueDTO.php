<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class MetadataValueDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $label
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
        ];
    }
}
