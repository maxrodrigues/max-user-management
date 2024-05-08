<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return new JsonResponse('0.0.0', 200);
})->name('healthy');

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');

Route::middleware([\App\Http\Middleware\ApiTokenMiddleware::class])->group(function () {
    Route::get('/profile', ProfileController::class)->name('profile');
});

