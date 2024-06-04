<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::get('stores', [StoreController::class, 'index']);
Route::get('stores/{store}', [StoreController::class, 'show']);
Route::get('stores/{store}/products', [ProductController::class, 'index']);
Route::get('stores/{store}/products/{product}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('stores', [StoreController::class, 'store']);
    Route::put('stores/{store}', [StoreController::class, 'update']);
    Route::delete('stores/{store}', [StoreController::class, 'destroy']);

    Route::post('stores/{store}/products', [ProductController::class, 'store']);
    Route::put('stores/{store}/products/{product}', [ProductController::class, 'update']);
    Route::delete('stores/{store}/products/{product}', [ProductController::class, 'destroy']);
});
