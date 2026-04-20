<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard/stats', [App\Http\Controllers\Api\DashboardController::class, 'stats']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/products/slow', [App\Http\Controllers\Api\ProductController::class, 'slow']);

    Route::apiResource('users', App\Http\Controllers\Api\UserController::class);
    Route::apiResource('products', App\Http\Controllers\Api\ProductController::class);


    Route::get('/get_all_products', [App\Http\Controllers\Api\ProductController::class, 'get_all']);
    Route::get('/get_all_users', [App\Http\Controllers\Api\UserController::class, 'get_all']);


    Route::post('/orders', [App\Http\Controllers\Api\OrderController::class, 'store']);
    Route::delete('/orders/{order}', [App\Http\Controllers\Api\OrderController::class, 'destroy']);
    Route::post('/orders/restore/{order}', [App\Http\Controllers\Api\OrderController::class, 'restore']);
    Route::get('/orders', [App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::get('/users/{id}/orders', [App\Http\Controllers\Api\OrderController::class, 'userOrders']);
});