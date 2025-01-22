<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RacecourseMstController;
use App\Http\Controllers\VotingRecordController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('racecourse_mst', RacecourseMstController::class);
Route::resource('voting_record', VotingRecordController::class);
Route::match(['get', 'post'], '/voting_record', [VotingRecordController::class, 'index'])->name('voting_record.index');
Route::get('voting_record/{id}/copy', [VotingRecordController::class, 'copy'])->name('voting_record.copy');
