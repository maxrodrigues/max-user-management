<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\ApiTokenMiddleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return new JsonResponse('0.0.0', 200);
})->name('healthy');

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');

Route::middleware(['auth:api'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/recover', [\App\Http\Controllers\RecoverController::class, 'recover'])->name('recover');
    Route::post('/change-password', [\App\Http\Controllers\RecoverController::class, 'changePassword'])->name('change-password');
});

