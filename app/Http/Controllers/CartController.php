<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function mount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => ['required','array', 'min:1'],
            'items.*' => ['numeric', 'min:1'],
        ], [
            'ids.required' => 'Os IDs dos produtos são obrigatórios.',
            'ids.array' => 'Os IDs dos produtos devem ser um array.',
            'ids.min' => 'Pelo menos um ID de produto deve ser fornecido.',
            'items.*.numeric' => 'Os itens do carrinho devem ser numéricos.',
            'items.*.min' => 'Os itens do carrinho devem ser pelo menos 1.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'products' => []
            ], 400);
        }

        $ids = $request->input('ids');
        $products = Product::with('images')->whereIn('id', $ids)->get();
        $formattedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'label' => $product->label,
                'price' => $product->price,
                'images' => asset('storage/' . ($product->images->first()->uri ?? 'products/image-not-found.jpeg')),
            ];
        });
        

        return response()->json([
            'error' => null,
            'products' => $formattedProducts
        ], 200);
    }

    public function shipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'zipcode' => ['required','string', 'min:5', 'max:10'],
        ], [
            'zipcode.required' => 'O CEP é obrigatório.',
            'zipcode.string' => 'O CEP deve ser uma string.',
            'zipcode.min' => 'O CEP deve ter pelo menos 5 caracteres.',
            'zipcode.max' => 'O CEP deve ter no máximo 10 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'products' => []
            ], 400);
        }

        return response()->json([
            'error' => null,
            'shipping' => [
                'zipecode' => $request->input('zipcode'),
                'price' => 20.00,
                'days' => 8
            ]
        ], 200);
    }
}
