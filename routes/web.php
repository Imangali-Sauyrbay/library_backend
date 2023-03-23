<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $r = rand(0, 100);
    if ($r > 75) {
        return response()->file(storage_path('app/img.jpg'));
    }

    if ($r > 50) {
        return response()->file(storage_path('app/arjunphp_laravel.png'));
    }

    return '<h1 style="text-align:center">Hello World with 50% chance!!! rand: ' . $r . '</h1>';
});
