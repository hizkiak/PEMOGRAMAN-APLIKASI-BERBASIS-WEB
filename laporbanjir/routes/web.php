<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;

Route::get('/', [LaporanController::class, 'create'])
    ->name('laporan.create');

Route::post('/laporan', [LaporanController::class, 'store'])
    ->name('laporan.store');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('laporan', LaporanController::class)
        ->except(['create', 'store']);
});
