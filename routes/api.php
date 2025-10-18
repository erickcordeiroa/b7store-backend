<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProductController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health-check', function() {
    return response()->json([
        'date' => Carbon::now(),
        'message' => 'project is ok!'
    ]);
});

Route::get('/banners', [BannerController::class, "index"]);
Route::get('/products', [ProductController::class, "index"]);
Route::get('/products/{slug}', [ProductController::class, "show"]);
Route::get('/products/{slug}/related', [ProductController::class, "related"]);