<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;

Route::view('/produk', 'produk.index')->name('produk.index');

Route::view('/produk/create', 'produk.create')->name('produk.create');

Route::get('/produk/{id}/edit', function ($id) {
    return view('produk.edit', ['id' => $id]);
})->name('produk.edit');

Route::get('/produk/{id}', function ($id) {
    return view('produk.show', ['id' => $id]);
})->whereNumber('id')->name('produk.show');
