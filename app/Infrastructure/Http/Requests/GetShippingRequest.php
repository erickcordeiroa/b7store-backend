<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetShippingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'zipcode' => ['required', 'string', 'min:5', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'zipcode.required' => 'O CEP é obrigatório.',
            'zipcode.string' => 'O CEP deve ser uma string.',
            'zipcode.min' => 'O CEP deve ter pelo menos 5 caracteres.',
            'zipcode.max' => 'O CEP deve ter no máximo 10 caracteres.',
        ];
    }
}
