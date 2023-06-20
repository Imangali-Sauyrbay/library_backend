<?php

use Illuminate\Http\Request;
use Modules\UserAuth\Entities\Record;
use Modules\UserAuth\Http\Controllers\AuthController;
use Modules\UserAuth\Http\Controllers\RegistrationLinkController;
use Modules\UserAuth\Http\Controllers\TokenController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register/{link?}', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('fill', [AuthController::class, 'fill']);
        Route::get('me', [AuthController::class, 'me']);
        Route::get('tokens', [TokenController::class, 'index']);
    });

    Route::prefix('reglink')->group(function() {
        Route::get('/', [RegistrationLinkController::class, 'index']);
        Route::post('/', [RegistrationLinkController::class, 'store']);
        Route::get('{uuid}', [RegistrationLinkController::class, 'show']);
    });
});
