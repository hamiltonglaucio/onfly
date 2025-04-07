<?php

use App\TravelService\UserInterface\Controllers\TravelRequestController;
use Illuminate\Support\Facades\Route;

Route::prefix('travels')
    ->middleware(['force.jwt','auth.api'])
    ->group(function () {
        Route::get('/', [TravelRequestController::class, 'index']);
        Route::post('/', [TravelRequestController::class, 'store']);
        Route::get('/user', [TravelRequestController::class, 'findByUserId']);
        Route::get('/filter', [TravelRequestController::class, 'filter']);
        Route::get('/notifications', [TravelRequestController::class, 'notifications']);
        Route::patch('/{id}', [TravelRequestController::class, 'updateStatus']);
        Route::patch('/{id}/cancel', [TravelRequestController::class, 'cancel']);
        Route::get('/{id}', [TravelRequestController::class, 'show']);
        Route::get('/status/{status}', [TravelRequestController::class, 'index']);

    });
