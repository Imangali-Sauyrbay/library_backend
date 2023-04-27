<?php

// Route::middleware('auth:api')->get('/library', function (Request $request) {
//    return $request->user();
// });

Route::prefix('v1/library')->group(function () {
    Route::get('/', 'LibraryController@index');
    Route::post('/', 'LibraryController@store');
});

Route::prefix('v1/books')->group(function () {
    Route::get('/', 'BookController@index');
    Route::post('/', 'BookController@store');
    Route::get('/{book}', 'BookController@show');
    Route::get('/{book}/cover', 'BookController@cover');
    Route::get('/{book}/ebook', 'BookController@pdf');
});
