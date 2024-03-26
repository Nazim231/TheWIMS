<?php

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

Route::get('/', function () {
    return view('login');
})->name('login');
Route::get('/signup', function () {
    return view('signup');
})->name('signup');


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('auth.login');
    Route::post('signup', 'createAccount')->name('auth.signup');
    Route::get('logout', 'logout')->name('auth.logout');
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function () {
        return view('welcome');
    })->name('home');
});
