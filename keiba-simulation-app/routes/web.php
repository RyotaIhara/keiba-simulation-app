<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RacecourseMstController;
use App\Http\Controllers\VotingRecordController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('racecourse_mst', RacecourseMstController::class);
Route::resource('voting_record', VotingRecordController::class);
