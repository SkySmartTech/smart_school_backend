<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserRegisterController;
use Illuminate\Support\Facades\Route;

Route::post('user-register', [UserRegisterController::class, 'store']);
Route::post('login', [LoginController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('add-new-user', []);
});
