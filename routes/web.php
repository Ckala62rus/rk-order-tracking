<?php

use App\Http\Controllers\Lk\UserController;
use Illuminate\Support\Facades\Route;

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

//Route::get('/', function () {
//    return view('welcome');
//})->name('main');

Auth::routes();

Route::get('/', [\App\Http\Controllers\Auth\LoginController::class, 'sign'])->name('/');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('order', \App\Http\Controllers\Lk\OrderController::class);
    Route::get('dashboard', [\App\Http\Controllers\Lk\OrderController::class, 'template']);
    Route::get('orders', [\App\Http\Controllers\Lk\OrderApiController::class, 'getOrders']); // vue for axios
    Route::get('status', [\App\Http\Controllers\Lk\OrderApiController::class, 'getStatuses']);
    Route::get('realisation-status', [\App\Http\Controllers\Lk\OrderApiController::class, 'getRealisationStatuses']);
    Route::get('vue', [\App\Http\Controllers\Lk\OrderController::class, 'vueOrders']);
    Route::get('users', [UserController::class, 'users']);
    Route::resource('admin/users', UserController::class)->middleware('admin');
});

//Route::get('realisation-status', [\App\Http\Controllers\Lk\OrderApiController::class, 'getRealisationStatuses']);
