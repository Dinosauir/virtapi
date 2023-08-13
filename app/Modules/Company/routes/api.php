<?php

use App\Modules\Company\Controllers\Api\V1\CompanyController;
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
            Route::apiResource('companies', CompanyController::class);
        });
    });
