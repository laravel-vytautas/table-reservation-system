<?php

use Illuminate\Http\Request;
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

Route::apiResource('restaurants', \App\Http\Controllers\RestaurantsController::class);
Route::apiResource('tables', \App\Http\Controllers\TablesController::class);
Route::post('tables/reserve', [\App\Http\Controllers\TablesController::class, 'reserve']);
Route::delete('tables/{table}/{reservation}/cancel-reservation', [\App\Http\Controllers\TablesController::class, 'cancelReservation']);
