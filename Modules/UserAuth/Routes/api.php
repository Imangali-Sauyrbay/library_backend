<?php

use Illuminate\Http\Request;
use Modules\UserAuth\Http\Controllers\AuthController;
use Modules\UserAuth\Http\Controllers\TokenController;

// Route::middleware('auth:api')->get('/userauth', function (Request $request) {
//    return $request->user();
// });

Route::prefix('v1/userauths')->group(function () {
    Route::get('/', function (Request $request) {
        return response('userauths is working!!!' . $request->ip());
    });
});

Route::prefix('v1/auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'registerUser']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::get('tokens', [TokenController::class, 'index']);
    });
});
