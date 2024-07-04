<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\V1\CartController;
use App\Http\Controllers\V1\SliderController;
use App\Http\Controllers\V1\TokenController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\FavoriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('check-token', [TokenController::class, 'checkToken']);
    Route::delete('user-delete',[UserController::class, 'delete']);

    // Routes for Favorites
    Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('favorite-add', [FavoriteController::class, 'store'])->name('favorite-add');
    Route::delete('favorite-remove', [FavoriteController::class, 'destroy'])->name('favorite-remove');
    Route::delete('favorites-remove-all', [FavoriteController::class, 'destroyAll'])->name('favorites-remove-all');
    Route::post('favorite-toggle', [FavoriteController::class, 'toggleFavorite'])->name('favorite-toggle');

    // Routes for Carts
    Route::get('carts', [CartController::class, 'index'])->name('carts');
    Route::post('cart-add', [CartController::class, 'store'])->name('cart-add');
    Route::post('cart-increase', [CartController::class, 'increaseQuantity'])->name('cart-increase');
    Route::post('cart-decrease', [CartController::class, 'decreaseQuantity'])->name('cart-decrease');
    Route::delete('cart-remove', [CartController::class, 'destroy'])->name('cart-remove');
    Route::delete('carts-remove-all', [CartController::class, 'destroyAll'])->name('carts-remove-all');
});
//Routes for Slider
Route::get('sliders',[SliderController::class, 'index'])->name('sliders');

//Routes for Brands
Route::get('brands',[BrandController::class, 'index'])->name('brands');

//Routes for Categories
Route::get('categories',[CategoryController::class, 'index'])->name('categories');

//Routes for Products
Route::get('products',[ProductController::class, 'index'])->name('products');
Route::get('products/{product}',[ProductController::class, 'show'])->name('products.show');

// Include all routes from auth.php
require __DIR__.'/auth.php';
