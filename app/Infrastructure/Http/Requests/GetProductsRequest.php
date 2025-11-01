<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProductsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'metadata' => ['sometimes', 'string'],
            'orderBy' => ['sometimes', 'in:views,selling,price'],
            'limit' => ['sometimes', 'numeric', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'metadata.string' => 'O metadata deve ser um JSON válido',
            'orderBy.in' => 'O campo orderBy deve ser um dos seguintes valores: views, selling, price',
            'limit.numeric' => 'O campo limit deve ser um número',
            'limit.min' => 'O campo limit deve ser pelo menos 1',
            'limit.max' => 'O campo limit deve ser no máximo 100',
        ];
    }

    public function getMetadataFilters(): array
    {
        if (!$this->filled('metadata')) {
            return [];
        }

        $metadata = json_decode($this->query('metadata'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return is_array($metadata) ? $metadata : [];
    }
}
