<?php

use Illuminate\Http\Request;

// Route::middleware('auth:api')->get('/library', function (Request $request) {
//    return $request->user();
// });

// TODO Change to plural!
Route::prefix('v1/library')->group(function () {
    Route::get('/', function (Request $request) {
        return response('library is working!!!' . $request->ip());
    });
});
