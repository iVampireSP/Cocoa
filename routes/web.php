<?php


use App\Http\Controllers\HostController;
use App\Http\Controllers\ServerController;
use Illuminate\Support\Facades\Route;
use ivampiresp\Cocoa\Http\AdminController;
use ivampiresp\Cocoa\Http\DeviceController;
use ivampiresp\Cocoa\Http\IndexController;
use ivampiresp\Cocoa\Http\ReplyController;
use ivampiresp\Cocoa\Http\UserController;
use ivampiresp\Cocoa\Http\WorkOrderController;

Route::get('/login', [IndexController::class, 'index'])->name('login');
Route::post('/login', [IndexController::class, 'login']);


// 登入后的路由
Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin'], function () {
    Route::get('/', [IndexController::class, 'index'])->name('earnings');

    Route::resource('users', UserController::class);
    Route::resource('servers', ServerController::class);
    Route::resource('hosts', HostController::class);
    Route::resource('admins', AdminController::class);
    Route::resource('devices', DeviceController::class);
    Route::resource('work-orders', WorkOrderController::class);
    Route::resource('work-orders.replies', ReplyController::class);


    Route::get('devices/{device}/allows', [DeviceController::class, 'allows'])->name('devices.allows.index');
    Route::post('devices/{device}/allows', [DeviceController::class, 'store_allow'])->name('devices.allows.store');
    Route::delete('devices/allows/{allow}', [DeviceController::class, 'allow_destroy'])->name('devices.allows.destroy');

    Route::get('mqtt', [DeviceController::class, 'online'])->name('mqtt.online');
    Route::delete('mqtt', [DeviceController::class, 'online_destroy'])->name('mqtt.kick');


    Route::post('/logout', [IndexController::class, 'logout'])->name('logout');
});
