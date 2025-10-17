<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request) {
        $validator = Validator::make($request->all(), [
            'metadata' => ['sometimes', 'string'],
            'orderBy' => ['sometimes', 'in:views,selling,price'],
            'limit' => ['sometimes', 'numeric', 'min:1', 'max:100']
        ], [
            'metadata' => 'O metadata deve ser um JSON válido',
            'orderBy' => 'O campo orderBy deve ser um dos seguintes valores: views, selling, price',
            'limit' => 'O campo limit deve ser um número',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'products' => []
            ], 400);
        }

        $metadata = [];
        if ( $request->filled('metadata')) {
            $metadata = json_decode($request->query('metadata'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'O metadata deve ser um JSON válido',
                    'products' => []
                ], 400);
            }
        } 

        $limit = $request->query('limit', 15);
        $orderBy = 'id';

        $query = Product::query()->with('metadata');

        foreach($metadata as $key => $value) {
            $query->whereHas('metadata', function ($q) use ($key, $value) {
                $q->where('category_metadata_id', $key)->where('metadata_value_id', $value);
            });
        }


        if ($request->query('orderBy')) {
            switch ($request->query('orderBy')) {
                case 'views':
                    $orderBy = 'views_count';
                    break;
                case 'selling':
                    $orderBy = 'sales_count';
                    break;
                case 'price':
                    $orderBy = 'price';
                    break;
            }
        }

        $query->with('images');
        $query->orderBy($orderBy, 'desc');
        $query->limit($limit);

        $products = $query->get();

        return response()->json([
            'error' => null,
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'label' => $product->label,
                    'price' => $product->price,
                    'image' => asset('storage/' . ($product->images->first()->uri ?? 'products/default.png')),
                    'liked' => false, //TODO: Implementar o Liked
                ];
            })
        ]);
    }
}
