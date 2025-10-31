<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.product_id' => ['required', 'numeric', 'exists:products,id'],
            'cart.*.quantity' => ['required', 'numeric', 'min:1'],
            'shipping_address_id' => ['required', 'numeric', 'exists:addresses,id'],
        ];
    }

    public function message(): array
    {
        return [
            'cart.min' => 'O carrinho deve conter pelo menos um item.',
            'shipping_address_id.required' => 'O ID do endereço de entrega é obrigatório.',
        ];
    }
}
