<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserRegisterController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\UserRole\UserRoleController;
use App\Http\Controllers\UserType\UserTypeController;
use Illuminate\Support\Facades\Route;

Route::post('user-register', [UserRegisterController::class, 'store']);
Route::post('login', [LoginController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'show']);
    Route::post('add-new-user', [UserController::class, 'store']);
    Route::get('all-users', [UserController::class, 'index']);
    Route::post('user/{id}/profile-update', [UserController::class, 'profileUpdate']);
    Route::post('user/{id}/update', [UserController::class, 'update']);

    Route::post('add-new-user-role', [UserRoleController::class, 'store']);
    Route::get('user-roles', [UserRoleController::class, 'index']);
    Route::get('user-role/{id}', [UserRoleController::class, 'show']);
    Route::post('update-user-role/{id}', [UserRoleController::class, 'update']);
    Route::delete('delete-user-role/{id}', [UserRoleController::class, 'destroy']);

    Route::post('add-new-user-type', [UserTypeController::class, 'store']);
    Route::get('user-types', [UserTypeController::class, 'index']);
    Route::get('user-type/{id}', [UserTypeController::class, 'show']);
    Route::post('update-user-type/{id}', [UserTypeController::class, 'update']);
    Route::delete('delete-user-type/{id}', [UserTypeController::class, 'destroy']);

});
