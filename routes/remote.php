<?php

/**
 * 远程路由 Remote
 * 这里的路由都会暴露给用户和平台，并且您也必须确保它们都经过 'Remote' 中间件，否则这些路由将不安全。
 *
 */


use Illuminate\Support\Facades\Route;
use ivampiresp\Cocoa\Http\Remote;

Route::get('/remote', [Remote\RemoteController::class, 'index']);
Route::post('/fast-login', [Remote\RemoteController::class, 'login']);

Route::apiResource('work-orders', Remote\WorkOrder\WorkOrderController::class);
Route::apiResource('work-orders.replies', Remote\WorkOrder\ReplyController::class);
Route::apiResource('hosts', Remote\HostController::class)->only(['show', 'update', 'destroy']);

// MQTT 部分
// 登录
Route::post('mqtt/authentication', [Remote\MqttController::class, 'authentication'])->name('mqtt.authentication');
// 授权
Route::post('mqtt/authorization', [Remote\MqttController::class, 'authorization'])->name('mqtt.authorization');

