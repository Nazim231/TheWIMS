<?php

use App\Http\Controllers\Admin\ShopsController as AdminManageShops;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'login')->name('login');
Route::view('/signup', 'signup')->name('signup');


// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('auth.login');
    Route::post('signup', 'createAccount')->name('auth.signup');
    Route::get('logout', 'logout')->name('auth.logout');
});

Route::group(['middleware' => 'auth'], function () {
    Route::view('/home', 'admin.home')->name('home');
    Route::view('/employees', 'admin.employees')->name('employees');
    // Shop Routes
    Route::controller(AdminManageShops::class)->group(function () {
        Route::get('/shops', 'showPage')->name('shops');
        Route::post('/shop', 'addShop')->name('shops.add');
    });
});
