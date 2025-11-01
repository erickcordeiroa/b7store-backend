<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MountCartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['numeric', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'Os IDs dos produtos são obrigatórios.',
            'ids.array' => 'Os IDs dos produtos devem ser um array.',
            'ids.min' => 'Pelo menos um ID de produto deve ser fornecido.',
            'ids.*.numeric' => 'Os itens do carrinho devem ser numéricos.',
            'ids.*.min' => 'Os itens do carrinho devem ser pelo menos 1.',
        ];
    }
}
