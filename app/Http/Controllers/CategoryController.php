<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function metadata(Request $request, $slug)
    {
        if (!is_string($slug) || empty($slug)) {
            return response()->json([
                'error' => 'Slug inválido',
                'category' => null,
                'metadata' => []
            ], 400);
        }

        $category = Category::where('slug', $slug)->with('metadata.values')->first();
        if (! $category) {
            return response()->json([
                'error' => 'Categoria não encontrada',
                'metadata' => []
            ], 404);
        }

        return response()->json([
            'error' => null,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug
            ],
            'metadata' => $category->metadata->map(function ($metadata) {
                return [
                    'id' => $metadata->id,
                    'label' => $metadata->label,
                    'values' => $metadata->values->map(function ($value) {
                        return [
                            'id' => $value->id,
                            'label' => $value->label
                        ];
                    })
                ];
            })
        ]);
    }
}
