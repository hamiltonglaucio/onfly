<?php

use Illuminate\Support\Facades\Route;
use App\AuthService\UserInterface\Controllers\AuthController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth.api')->group(function () {
  Route::get('/auth/me', [AuthController::class, 'me']);
  Route::post('/auth/logout', [AuthController::class, 'logout']);
  Route::post('/auth/refresh', [AuthController::class, 'refresh']);
});
