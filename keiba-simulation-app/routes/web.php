<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RacecourseMstController;
use App\Http\Controllers\VotingRecordController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('racecourse_mst', RacecourseMstController::class);
Route::get('voting_record/{id}/copy', [VotingRecordController::class, 'copy'])->name('voting_record.copy');
Route::get('voting_record/totalling', [VotingRecordController::class, 'totalling'])->name('voting_record.totalling');
Route::get('voting_record/createSpecialMethod', [VotingRecordController::class, 'createSpecialMethod'])->name('voting_record.createSpecialMethod');
Route::post('voting_record/storeSpecialMethod', [VotingRecordController::class, 'storeSpecialMethod'])->name('voting_record.storeSpecialMethod');
Route::resource('voting_record', VotingRecordController::class);
