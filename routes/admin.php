<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\SellerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest:admin')->group(function(){
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth:admin')->group(function(){
    //  Auth
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', function (){
        return Inertia::render('Admin/Dashboard');
    })->name('home');

    Route::resource('sellers', SellerController::class);

});
