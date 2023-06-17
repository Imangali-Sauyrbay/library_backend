<?php

use Illuminate\Http\Request;
use Modules\UserAuth\Entities\Record;
use Modules\UserAuth\Http\Controllers\AuthController;
use Modules\UserAuth\Http\Controllers\TokenController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'registerUser']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::get('tokens', [TokenController::class, 'index']);
    });
});

Route::prefix('records')->group(function () {
    Route::get('/', function ()
    {
        /** @var User */
        $user = auth()->user();
        if(!$user) {
            return response(401);
        }

        if($user->isCoworker()) {
            return Record::with('user')->get();
        }

        return $user->records;
    });

});
