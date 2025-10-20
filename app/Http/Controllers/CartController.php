<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.product_id' => ['required', 'numeric', 'exists:products,id'],
            'cart.*.quantity' => ['required', 'numeric', 'min:1'],
            'shipping_address_id' => ['required', 'numeric', 'exists:addresses,id'],
        ], [
            'cart.min' => 'O carrinho deve conter pelo menos um item.',
            'shipping_address_id.required' => 'O ID do endereço de entrega é obrigatório.',
        ]); 

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();

        //Validation of shipping address belongs to user
        $address = $user
            ->addresses()
            ->where('id', $request->input('shipping_address_id'))
            ->first();
        if (!$address) {
            return response()->json([
                'error' => 'Endereço de entrega não pertence ao usuário logado.'
            ], 400);
        }

        //Get all products exists in cart 
        $products = Product::whereIn('id', array_column($request->input('cart'), 'product_id'))
            ->get();
    

        //Calculate total price of cart
        $total = 0;
        foreach ($request->input('cart') as $cartItem) {
            $product = $products->where('id', $cartItem['product_id'])->first();
            if ($product) {
                $total += $product->price * $cartItem['quantity'];
            }
        }

        $order = Order::create([
            'user_id' => $user->id,
            'tracking_code' => uniqid('order_'),
            'total_amount' => $total,
            'status' => 'pending',
            'shipping_cost' => 20.00,
            'shipping_days' => 7,
            'shipping_zipcode' => $address->zipcode,
            'shipping_street' => $address->street,
            'shipping_number' => $address->number,
            'shipping_complement' => $address->complement,
            'shipping_city' => $address->city,
            'shipping_state' => $address->state,
            'shipping_country' => $address->country,
        ]);

        $order->products()->createMany(
            array_map(function ($cartItem) use ($products) {
                $product = $products->where('id', $cartItem['product_id'])->first();
                return [
                    'product_id' => $cartItem['product_id'],
                    'quantity' => $cartItem['quantity'],
                    'price' => $product ? $product->price : 0,
                ];
            }, $request->input('cart'))
        );

        // Lógica de checkout a ser implementada
        return response()->json([
            'error' => null,
            'order' => [
                'id' => $order->id,
                'tracking_code' => $order->tracking_code,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'shipping_cost' => $order->shipping_cost,
                'shipping_days' => $order->shipping_days,
                'shipping_address' => [
                    'zipcode' => $order->shipping_zipcode,
                    'street' => $order->shipping_street,
                    'number' => $order->shipping_number,
                    'complement' => $order->shipping_complement,
                    'city' => $order->shipping_city,
                    'state' => $order->shipping_state,
                    'country' => $order->shipping_country,
                ],
                'products' => $order->products()->get()->map(function ($product) {
                    return [
                        'product_id' => $product->product_id,
                        'quantity' => $product->quantity,
                        'price' => $product->price,
                    ];
                }),

            ],
        ], 200);
    }
}
