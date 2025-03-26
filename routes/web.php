<?php

use App\Http\Controllers\KeyLoggerController;
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

    /* RDP Cabinet */
    Route::get("rdp", [\App\Http\Controllers\Lk\RdpController::class, 'index']);

    Route::post("user", [UserController::class, 'getMe']);
    Route::get("api/rdp/statistic", [\App\Http\Controllers\Lk\RdpController::class, 'getStatistics']);
    Route::get("api/rdp/users", [\App\Http\Controllers\Lk\RdpController::class, 'getUsers']);
//    Route::post("api/rdp/detail", [\App\Http\Controllers\Lk\RdpController::class, 'getDetailByUser']);

    /* Win service */
    Route::get('windows/server', [\App\Http\Controllers\WinService\WinServerController::class, 'templateServer']);
    Route::get('windows/dashboard', [\App\Http\Controllers\WinService\WinServerController::class, 'templateDashboard']);
    Route::get('windows/service/{id}', [\App\Http\Controllers\WinService\WinServiceController::class, 'templateService']);
    Route::post('windows/service/enable', [\App\Http\Controllers\WinService\WinServiceController::class, 'setEnable']);

    Route::post('windows/server/enable', [\App\Http\Controllers\WinService\WinServerController::class, 'setEnable']);
    Route::get('windows/server/services', [\App\Http\Controllers\WinService\WinServerController::class, 'getServices']);
    Route::post('windows/server/services-start', [\App\Http\Controllers\WinService\WinServerController::class, 'startService']);
    Route::post('windows/server/services-stop', [\App\Http\Controllers\WinService\WinServerController::class, 'stopService']);
    Route::get('windows/server-update', [\App\Http\Controllers\WinService\WinServerController::class, 'updateInfoByServers']);

    Route::resource("api/win/server", \App\Http\Controllers\WinService\WinServerController::class);
    Route::resource("api/win/service", \App\Http\Controllers\WinService\WinServiceController::class);

    /* KeyLogger */
    Route::resource('key-logger', KeyLoggerController::class);
    Route::get('key-logger-dashboard', [KeyLoggerController::class, 'templateDashboard']);
    Route::get('key-logger-detail-group', [KeyLoggerController::class, 'detailGroupInformation']);

    /* LoginKeyLogger */
    Route::get('key-logger-logins', [\App\Http\Controllers\LoginKeyLoggerController::class, 'index']);

    /* Export Excel */
    Route::get('export', [\App\Http\Controllers\KeyLoggerController::class, 'export']);
});

Route::post("api/rdp/detail", [\App\Http\Controllers\Lk\RdpController::class, 'getDetailByUser']);
Route::any('bot', [\App\Http\Controllers\Telegram\TelegramController::class, 'callbackTelegramApi']);
Route::get('job', function (){
    \App\Jobs\TestJob::dispatch()->onQueue("testing");
});

Route::get('tsd', [\App\Http\Controllers\TsdController::class, 'form']);
Route::post('tsd/api', [\App\Http\Controllers\TsdController::class, 'findPart']);

//Route::get('/mailable', function () {
//    $user = App\Models\User::find(1);
//
//    \Illuminate\Support\Facades\Mail::send(new \App\Mail\TestMail($user));
////    return new App\Mail\TestMail($user);
//});

//Route::get('test', [KeyLoggerController::class, 'test']);
