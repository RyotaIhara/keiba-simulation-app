<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RacecourseMstController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('racecourse_mst', RacecourseMstController::class);
