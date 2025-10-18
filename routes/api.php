<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Carbon\Carbon;
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
Route::get('/categories/{slug}/metadata', [CategoryController::class, "metadata"]);
Route::post('/cart/mount', [CartController::class, "mount"]);
Route::get('/cart/shipping', [CartController::class, "shipping"]);

Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);
Route::post('/user/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');