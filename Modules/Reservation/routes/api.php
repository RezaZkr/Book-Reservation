<?php

use Illuminate\Support\Facades\Route;
use Modules\Reservation\Http\Controllers\Api\V1\LoanController;
use Modules\Reservation\Http\Controllers\Api\V1\ReservationController;
use Modules\User\Http\Middleware\RestrictedUserMiddleware;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum'])->prefix('v1/loan')->as('v1.loan.')->group(function () {

    Route::post('/', [LoanController::class, 'loan'])->name('loan')->middleware(RestrictedUserMiddleware::class);
    Route::post('/return', [LoanController::class, 'return'])->name('return');

});

Route::middleware(['auth:sanctum'])->prefix('v1/reserve')->as('v1.reserve.')->group(function () {

    Route::post('/', [ReservationController::class, 'reserve'])->name('reserve')->middleware(RestrictedUserMiddleware::class);

});
