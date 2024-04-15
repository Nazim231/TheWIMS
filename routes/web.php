<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employee\HomeController;
use App\Http\Controllers\Employee\StocksController;
use App\Http\Controllers\Admin\ShopsController as AdminManageShops;
use App\Http\Controllers\Admin\StocksController as AdminManageStocks;
use App\Http\Controllers\Admin\EmployeesController as AdminManageEmployees;
use App\Http\Controllers\Admin\CategoriesController as AdminManageCategories;

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


Route::get('/', function () {
    if (Auth::check())
        return redirect()->route(Auth::user()->is_admin ? 'admin.home' : 'employee.home');
    else
        return redirect()->route('login');
});
// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::get('/signup', 'showSignUp')->name('signup');
    Route::post('login', 'login')->name('auth.login');
    Route::post('signup', 'createAccount')->name('auth.signup');
    Route::get('logout', 'logout')->name('auth.logout');
});

Route::group(['middleware' => 'auth'], function () {

    Route::group(['middleware' => 'is_admin', 'prefix' => 'admin/', 'as' => 'admin.'], function () {

        Route::view('/', 'admin.home')->name('home');
        // Shop Routes
        Route::controller(AdminManageShops::class)->group(function () {
            Route::get('/shops', 'showPage')->name('shops');
            Route::post('/shop', 'addShop')->name('shops.add');
        });
        // Employee Routes
        Route::controller(AdminManageEmployees::class)->group(function () {
            Route::get('/employees', 'showPage')->name('employees');
            Route::post('/employee', 'addEmployee')->name('employees.add');
        });
        // Category Routes
        Route::controller(AdminManageCategories::class)->group(function () {
            Route::get('/categories', 'showPage')->name('categories');
            Route::post('/category', 'addCategory')->name('categories.add');
        });
        // Stock Routes
        Route::controller(AdminManageStocks::class)->group(function () {
            Route::get('/stocks', 'showPage')->name('stocks');
            Route::post('/stock', 'addStock')->name('stocks.add');
            Route::get('/stock/{id}', 'showProduct')->name('stocks.product');
            Route::post('/add-variation', 'addVariations')->name('stocks.product.add');
        });
    });

    Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
        Route::controller(HomeController::class)->group(function () {
            Route::get('/', 'index')->name('home');
        });

        Route::controller(StocksController::class)->group(function () {
            Route::get('/stocks', 'index')->name('stocks');
            Route::get('/add-stocks', 'addStockToShopPage')->name('products.request.page');
            Route::post('/select-variations', 'getSelectedProductVariations')->name('stocks.request.variations');
        });
    });
});
