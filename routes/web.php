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
//    Route::get('realisation-status', [\App\Http\Controllers\Lk\OrderApiController::class, 'getRealisationStatuses']);
    Route::get('vue', [\App\Http\Controllers\Lk\OrderController::class, 'vueOrders']);
    Route::get('users', [UserController::class, 'users']);
    Route::resource('admin/users', UserController::class)->middleware('admin');
    Route::get('detail/orders', [\App\Http\Controllers\Lk\OrderApiController::class, 'getDetailInformationFromModal']);
    Route::get('manager/order/table', [\App\Http\Controllers\Lk\OrderController::class, 'managerOrderTable']);
    Route::get('manager/order/companies', [\App\Http\Controllers\Lk\OrderApiController::class, 'getCompanies']);
    Route::get('zip/orders', [\App\Http\Controllers\Lk\OrderController::class, 'zipOrderTable']);

    Route::get('realisation-status', [\App\Http\Controllers\Lk\OrderApiController::class, 'getRealisationStatuses']);
    Route::get('test', [\App\Http\Controllers\Lk\OrderApiController::class, 'newOrder']);
    Route::get('zip/detail/{id}', [\App\Http\Controllers\Lk\OrderController::class, 'zipOrderDetail']);
    Route::get('zip/detail-orders', [\App\Http\Controllers\Lk\OrderApiController::class, 'getDetailZipOrders']);
    Route::get('zip/detail-orders/group/{id}', [\App\Http\Controllers\Lk\OrderController::class, 'zipOrderDetailGroup']);
    Route::get('zip/detail-orders/group', [\App\Http\Controllers\Lk\OrderApiController::class, 'getDetailZipOrderByGroup']);

});

Route::any('bot', [\App\Http\Controllers\Telegram\TelegramController::class, 'callbackTelegramApi']);
Route::get('job', function (){
    \App\Jobs\TestJob::dispatch()->onQueue("testing");
});
