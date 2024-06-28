<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\V1\SliderController;
use App\Http\Controllers\V1\TokenController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('check-token', [TokenController::class, 'checkToken']);

    Route::delete('user-delete',[UserController::class, 'delete']);
});

//Routes for Slider
Route::get('sliders',[SliderController::class, 'index']);

//Routes for Brands
Route::get('brands',[BrandController::class, 'index']);

//Routes for Categories
Route::get('categories',[CategoryController::class, 'index']);

//Routes for Products
Route::get('products',[ProductController::class, 'index']);
Route::get('products/{product}',[ProductController::class, 'show']);

// Include all routes from auth.php
require __DIR__.'/auth.php';
