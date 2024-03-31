<?php

use App\Http\Controllers\Admin\ShopsController as AdminManageShops;
use App\Http\Controllers\Admin\EmployeesController as AdminManageEmployees;
use App\Http\Controllers\Admin\CategoriesController as AdminManageCategories;
use App\Http\Controllers\Admin\StocksController as AdminManageStocks;
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
    // Shop Routes
    Route::controller(AdminManageShops::class)->group(function () {
        Route::get('/shops', 'showPage')->name('shops');
        Route::post('/shop', 'addShop')->name('shops.add');
    }); 
    // Employees Routes
    Route::controller(AdminManageEmployees::class)->group(function () {
        Route::get('/employees', 'showPage')->name('employees');
        Route::post('/employee', 'addEmployee')->name('employees.add');
    });
    // Categories Routes
    Route::controller(AdminManageCategories::class)->group(function () {
        Route::get('/categories', 'showPage')->name('categories');
        Route::post('/category', 'addCategory')->name('categories.add');
    });

    Route::controller(AdminManageStocks::class)->group(function () {
        Route::get('/stocks', 'showPage')->name('stocks');
        Route::post('/stock', 'addStock')->name('stocks.add');
    });
});
