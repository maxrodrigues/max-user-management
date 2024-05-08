<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return new \Illuminate\Http\JsonResponse('0.0.0', 200);
})->name('healthy');

Route::post('/register', \App\Http\Controllers\RegisterController::class)->name('register');
