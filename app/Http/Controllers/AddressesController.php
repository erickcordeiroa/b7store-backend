<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddressesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $addresses = $user->addresses()->get([
            'id', 
            'zipcode', 
            'street', 
            'number', 
            'city', 
            'state', 
            'country', 
            'complement'
        ]);

        return response()->json([
            'error' => null,
            'addresses' => $addresses
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'zipcode' => ['required', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
        ], [
            'zipcode.required' => 'O CEP é obrigatório.',
            'street.required' => 'O logradouro é obrigatório.',
            'city.required' => 'A cidade é obrigatória.',
            'number.required' => 'O número é obrigatório.',
            'complement.max' => 'O complemento deve ter no máximo 255 caracteres.',
            'state.required' => 'O estado é obrigatório.',
            'country.required' => 'O país é obrigatório.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'addresses' => null
            ], 400);
        }

        $user = Auth::user();
        $address = $user->addresses()->create($request->all());

        return response()->json([
            'error' => null,
            'addresses' => [
                "id" => $address->id,
                "zipcode" => $address->zipcode,
                "street" => $address->street,
                "number" => $address->number,
                "city" => $address->city,
                "state" => $address->state,
                "country" => $address->country,
                "complement" => $address->complement
            ]
        ], 201);
    }
}
