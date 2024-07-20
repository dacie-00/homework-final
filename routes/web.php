<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
//    Route::get('/accounts/{checkingAccount}', [AccountController::class, 'show'])->name('accounts.show');
    Route::get('/accounts/{checkingAccount:uuid}', [AccountController::class, 'show'])->name('accounts.show');
});

require __DIR__.'/auth.php';
