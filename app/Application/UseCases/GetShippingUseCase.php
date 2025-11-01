<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\ShippingDTO;

class GetShippingUseCase
{
    public function execute(string $zipcode): ShippingDTO
    {
        // TODO: Implementar integração com API de frete
        return new ShippingDTO(
            zipcode: $zipcode,
            price: 20.00,
            days: 8
        );
    }
}
