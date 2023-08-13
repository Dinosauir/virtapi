<?php

use App\Modules\Station\Controllers\Api\V1\StationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('api')
    ->prefix('api')
    ->as('api')
    ->group(static function () {
        Route::group(['prefix' => 'v1', 'as' => 'v1'], function () {
            Route::post('/stations/search', [StationController::class, 'searchInRadius']);
            Route::apiResource('stations', StationController::class);
        });
    });