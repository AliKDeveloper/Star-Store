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
    Route::delete('user-delete', [UserController::class, 'delete']);

    // Routes for Favorites
    Route::group(['controller' => FavoriteController::class, 'prefix' => 'favorites'], function () {
        //127.0.0.1:8000/api/v1/favorites   = favorites.index
        Route::get('/', 'index')->name('favorites.index');
        Route::post('/add', 'store')->name('favorites.store');
        Route::delete('/remove/{product}', 'destroy')->name('favorites.destroy');
        Route::delete('/remove-all', 'destroyAll')->name('favorites.destroyAll');
        Route::post('/toggle', 'toggleFavorite')->name('favorites.toggle');
    });

    // Routes for Carts
    Route::group(['controller' => CartController::class, 'prefix' => 'carts'], function () {
        Route::get('/', 'index')->name('carts.index');
        Route::post('/add', 'store')->name('carts.store');
        Route::post('/increase', 'increaseQuantity')->name('carts.increase');
        Route::post('/decrease', 'decreaseQuantity')->name('carts.decrease');
        Route::delete('/remove/{product}', 'destroy')->name('carts.destroy');
        Route::delete('/remove-all', 'destroyAll')->name('carts.destroyAll');
    });

    // Routes for Sliders
    Route::group(['controller' => SliderController::class, 'prefix' => 'sliders'], function () {
        Route::get('/', 'index')->name('sliders.index');
    });

    // Routes for Brands
    Route::group(['controller' => BrandController::class, 'prefix' => 'brands'], function () {
        Route::get('/', 'index')->name('brands.index');
    });

    // Routes for Categories
    Route::group(['controller' => CategoryController::class, 'prefix' => 'categories'], function () {
        Route::get('/', 'index')->name('categories.index');
    });

    // Routes for Products
    Route::group(['controller' => ProductController::class, 'prefix' => 'products'], function () {
        Route::get('/', 'index')->name('products.index');
        Route::get('/{product}', 'show')->name('products.show');
    });

});

// Include all routes from auth.php
require __DIR__ . '/auth.php';
