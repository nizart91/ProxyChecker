<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return inertia('app');
//});

Route::get('/', [\App\Http\Controllers\TaskController::class, 'dashboard'])->name('dashboard');

Route::resource('tasks', \App\Http\Controllers\TaskController::class);
