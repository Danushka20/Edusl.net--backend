<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DocumentController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('batches', BatchController::class);
Route::resource('students', StudentController::class);
Route::resource('documents', DocumentController::class);
