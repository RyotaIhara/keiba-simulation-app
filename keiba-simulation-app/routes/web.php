<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RacecourseMstController;
use App\Http\Controllers\VotingRecordController;

// 認証チェックを除外するルート一覧
Route::withoutMiddleware([\App\Http\Middleware\CheckLogin::class])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::resource('user', UserController::class);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('racecourse_mst', RacecourseMstController::class);
});

// 投票機能関連
Route::get('voting_record/totalling', [VotingRecordController::class, 'totalling'])->name('voting_record.totalling');
Route::get('voting_record/createSpecialMethod', [VotingRecordController::class, 'createSpecialMethod'])->name('voting_record.createSpecialMethod');
Route::post('voting_record/storeSpecialMethod', [VotingRecordController::class, 'storeSpecialMethod'])->name('voting_record.storeSpecialMethod');
Route::get('voting_record/{id}/copy', [VotingRecordController::class, 'copy'])->name('voting_record.copy');
Route::get('/voting_record', [VotingRecordController::class, 'index'])->name('voting_record.index'); // 一覧表示
Route::get('/voting_record/create', [VotingRecordController::class, 'create'])->name('voting_record.create'); // 新規作成フォーム
Route::post('/voting_record', [VotingRecordController::class, 'store'])->name('voting_record.store'); // 新規作成処理
//Route::get('/voting_record/{id}', [VotingRecordController::class, 'show'])->name('voting_record.show'); // 詳細表示
Route::get('/voting_record/{id}/edit', [VotingRecordController::class, 'edit'])->name('voting_record.edit'); // 編集フォーム
Route::put('/voting_record/{id}', [VotingRecordController::class, 'update'])->name('voting_record.update'); // 更新処理
Route::patch('/voting_record/{id}', [VotingRecordController::class, 'update'])->name('voting_record.update'); // 更新処理 (PUTと同じ)
Route::delete('/voting_record/{id}', [VotingRecordController::class, 'destroy'])->name('voting_record.destroy'); // 削除処理

/*
Route::middleware([\App\Http\Middleware\CheckLogin::class])->group(function () {
    Route::get('voting_record/{id}/copy', [VotingRecordController::class, 'copy'])->name('voting_record.copy');
});
*/
