<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BundleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\SellerController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function(){
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login');
});

Route::middleware('auth:admin')->group(function(){
    //  Auth
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', DashboardController::class)->name('home');

    Route::resource('sellers', SellerController::class)->except(['show', 'create', 'store']);

    Route::resource('categories', CategoryController::class)->except(['show']);

    Route::resource('products', ProductController::class)->except(['show', 'create', 'store']);

    Route::resource('bundles', BundleController::class)->except(['show', 'create', 'store']);

    Route::resource('admins', AdminController::class)->except(['show']);

    Route::resource('codes', PromoCodeController::class);
});
