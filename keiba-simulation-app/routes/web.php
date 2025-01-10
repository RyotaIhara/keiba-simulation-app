<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RacecourseMstController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('items', ItemController::class);
Route::resource('racecourse_mst', RacecourseMstController::class);
