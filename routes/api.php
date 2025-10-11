<?php

use App\Http\Controllers\BannerController;
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