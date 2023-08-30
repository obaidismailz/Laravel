<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
//use Illuminate\Auth\Notifications\ResetPassword;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\PasswordResetRequestController;


Route::group(['middleware' => 'api'], function ($routes) {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/profile', [UserController::class, 'profile']);
    Route::post('/refresh', [UserController::class, 'refresh']);
    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store'])->middleware('auth.jwt');
    Route::post('/posts/{id}', [PostController::class, 'update'])->middleware('auth.jwt');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->middleware('auth.jwt');

    Route::post('/reset-password-request', [PasswordResetRequestController::class, 'sendPasswordResetEmail']);
    Route::post('/change-password', [ChangePasswordController::class, 'passwordResetProcess']);
});
