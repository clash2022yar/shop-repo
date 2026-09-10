<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;

Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/product/{id}', [ProductApiController::class, 'show']);
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/cart/add', [ProductApiController::class, 'addToCart']);
});
