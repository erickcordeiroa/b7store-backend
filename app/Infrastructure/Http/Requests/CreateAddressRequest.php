<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'zipcode' => ['required', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'zipcode.required' => 'O CEP é obrigatório.',
            'street.required' => 'O logradouro é obrigatório.',
            'city.required' => 'A cidade é obrigatória.',
            'number.required' => 'O número é obrigatório.',
            'complement.max' => 'O complemento deve ter no máximo 255 caracteres.',
            'state.required' => 'O estado é obrigatório.',
            'country.required' => 'O país é obrigatório.',
        ];
    }
}
