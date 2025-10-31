<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetRelatedProductsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit' => ['sometimes', 'numeric', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'limit.numeric' => 'O campo limit deve ser um número',
            'limit.min' => 'O campo limit deve ser pelo menos 1',
            'limit.max' => 'O campo limit deve ser no máximo 100',
        ];
    }
}
