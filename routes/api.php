<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductsController::class, 'index']);
        Route::post('/', [ProductsController::class, 'store']);
        Route::get('/{id}', [ProductsController::class, 'show']);
        Route::put('/{id}', [ProductsController::class, 'update']);
        Route::delete('/{id}', [ProductsController::class, 'destroy']);
    });
});
