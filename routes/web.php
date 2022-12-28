<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('seller.login'),
        'canRegister' => Route::has('seller.register'),
    ]);
});

Route::post('/locale', function(){
   if(app()->getLocale() == 'en')
       return session()->put('locale', 'ru');
   else
       return session()->put('locale', 'en');
})->name('locale');
