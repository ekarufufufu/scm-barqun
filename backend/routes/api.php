<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\InventoryController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\api\StockInController;
use App\Http\Controllers\api\StockOpnameController;
use App\Http\Controllers\api\StockOutController;
use App\Http\Controllers\api\SupplierController;
use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::post('register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', function () {
        return auth()->user();
    });

    Route::apiResource('product', ProductController::class);
    Route::apiResource('supplier', SupplierController::class);
    Route::apiResource('customer', CustomerController::class);
    Route::apiResource('inventory', InventoryController::class);
    Route::apiResource('order', OrderController::class);
    Route::apiResource('stockin', StockInController::class);
    Route::apiResource('stockout', StockOutController::class);
    Route::apiResource('stockopname', StockOpnameController::class);
    Route::apiResource('role', RoleController::class);
    Route::apiResource('user', UserController::class);

    Route::post('logout', [AuthController::class, 'logout']);
});
