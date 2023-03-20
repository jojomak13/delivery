<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BundleController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\TypeController;
use Illuminate\Support\Facades\Route;

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

Route::get('categories', [TypeController::class, 'index']);
Route::get('categories/{type}', [TypeController::class, 'show']);
Route::get('store/{store}', [StoreController::class, 'index']);
Route::get('product/{product}', [StoreController::class, 'show']);

Route::get('bundles', [BundleController::class, 'index']);
Route::get('bundles/{bundle}', [BundleController::class, 'show']);


Route::prefix('user')->group(function(){
    Route::prefix('auth')->group(function(){
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });
    
    Route::middleware('auth:sanctum')->group(function(){
        Route::prefix('auth')->group(function() {
            Route::post('logout', [AuthController::class, 'logout']);
        });
        
        // Profile
        Route::get('profile', [ProfileController::class, 'edit']);
        Route::patch('profile', [ProfileController::class, 'update']);
        
        Route::apiResource('favorites', FavoriteController::class)->except(['update']);
        
        Route::apiResource('cart', CartController::class)->except(['show']);
    });
});
