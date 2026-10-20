<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('produk', ProdukController::class);

// Route::prefix('v1')->group(function () {
//     Route::apiResource('produk', ProdukController::class);
// });