<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class BannerDTO
{
    public function __construct(
        public readonly string $uri,
        public readonly string $link
    ) {
    }

    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'link' => $this->link,
        ];
    }
}
