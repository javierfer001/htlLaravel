<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KeyController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TechnicianController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::controller(VehicleController::class)->prefix('vehicles')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{vehicle}', 'show');
    Route::put('/{vehicle}', 'update');
    Route::delete('/{vehicle}', 'destroy');
});

Route::controller(KeyController::class)->prefix('keys')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{key}', 'show');
    Route::put('/{key}', 'update');
    Route::delete('/{key}', 'destroy');
});

Route::controller(TechnicianController::class)->prefix('technicians')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{technician}', 'show');
    Route::put('/{technician}', 'update');
    Route::delete('/{technician}', 'destroy');
});

Route::controller(OrderController::class)->prefix('orders')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{order}', 'show');
    Route::put('/{order}', 'update');
    Route::delete('/{order}', 'destroy');
});
