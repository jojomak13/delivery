<?php

use App\Http\Controllers\Seller\BranchController;
use App\Http\Controllers\Seller\BundleController;
use App\Http\Controllers\Seller\CategoryController;
use App\Http\Controllers\Seller\ExtraController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\StoreController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth:seller'])->group(function(){
    Route::get('/home', function () {
        return Inertia::render('Dashboard');
    })->name('home');

    Route::get('store/create', [StoreController::class, 'create'])->name('store.create');
    Route::post('store', [StoreController::class, 'store'])->name('store.store');
});

Route::middleware(['auth:seller', 'hasStore'])->group(function(){
    Route::get('store', [StoreController::class, 'index'])->name('store.index');
    Route::get('store/edit', [StoreController::class, 'edit'])->name('store.edit');
    Route::patch('store', [StoreController::class, 'update'])->name('store.update');

    Route::resource('branches', BranchController::class)->except('index');

    Route::resource('categories', CategoryController::class);

    Route::resource('extras', ExtraController::class);

    Route::resource('products', ProductController::class);

    Route::resource('bundles', BundleController::class);

});

require __DIR__.'/auth.php';
