<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class ErrorResponseDTO
{
    public function __construct(
        public readonly string $message,
        public readonly int $statusCode,
        public readonly ?array $errors = null,
        public readonly ?string $trace = null
    ) {
    }

    public function toArray(): array
    {
        $response = [
            'error' => $this->message,
        ];

        if ($this->errors !== null) {
            $response['errors'] = $this->errors;
        }

        if ($this->trace !== null) {
            $response['trace'] = $this->trace;
        }

        return $response;
    }
}

