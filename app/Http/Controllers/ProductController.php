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
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'image' => asset('storage/' . ($product->images->first()->uri ?? 'products/default.png')),
                    'liked' => false, //TODO: Implementar o Liked
                ];
            })
        ]);
    }

    public function show(string $slug) 
    {
        $product = Product::with(['images', 'category'])->where('slug', $slug)->first();

        if (!$product) {
            return response()->json([
                'error' => 'Produto não encontrado',
                'product' => null,
                'category' => null,
            ], 404);
        }

        $product->increment('views_count');

        $images = ['uri' => asset('storage/products/image-not-found.jpeg')];
        if (! $product->images->isEmpty() ) {
            $images = $product->images->map(function ($image) {
                return asset('storage/' . $image->uri);
            })->toArray();
        }


        return response()->json([
            'error' => null,
            'product' => [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'label' => $product->label,
                'description' => $product->description,
                'price' => $product->price,
                'images' => $images,
            ],
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ],
        ]);
    }

    public function related(Request $request, string $slug) 
    {
        $validator = Validator::make($request->all(), 
        ['limit' => ['sometimes', 'numeric', 'min:1', 'max:100']],
         ['limit' => 'O campo limit deve ser um número']);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'products' => []
            ], 400);
        }

        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            return response()->json([
                'error' => 'Produto não encontrado',
                'products' => [],
            ], 404);
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')
            ->limit($request->query('limit', 10))
            ->get();

        return response()->json([
            'error' => null,
            'products' => $relatedProducts->map(function ($product) {
                return [
                    'id' => $product->id,
                    'label' => $product->label,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'image' => asset('storage/' . ($product->images->first()->uri ?? 'products/image-not-found.jpeg')),
                    'liked' => false, //TODO: Implementar o Liked
                ];
            })
        ]);
    }
}
