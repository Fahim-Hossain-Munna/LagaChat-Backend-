<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\frontend\AuthenticateController;
use App\Http\Controllers\frontend\UserController;
use App\Http\Middleware\ApiGuardMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('login', [AuthenticateController::class, 'login']);
Route::post('register', [AuthenticateController::class, 'register']);

Route::middleware('api_guard')->group(function () {
    Route::get('/users', [UserController::class, 'list']);

    Route::prefix('/chats')->controller(ChatController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
    });
});
