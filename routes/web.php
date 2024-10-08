<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CryptoController;
use App\Http\Controllers\CryptoTransactionController;
use App\Http\Controllers\MoneyTransferController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        return redirect(route('account.index'));
    })->name('dashboard');

    Route::get('/accounts', [AccountController::class, 'index'])->name('account.index');
    Route::post('/accounts', [AccountController::class, 'store'])->name('account.store');
    Route::get('/accounts/create', [AccountController::class, 'create'])->name('account.create');
    Route::get('/accounts/{account}', [AccountController::class, 'show'])->name('account.show');
    Route::delete('/accounts/{account}', [AccountController::class, 'delete'])->name('account.delete');

    Route::get('/money-transfer/create', [MoneyTransferController::class, 'create'])->name('money-transfer.create');
    Route::post('/money-transfer/store', [MoneyTransferController::class, 'store'])->name('money-transfer.store');

    Route::get('/crypto', [CryptoController::class, 'index'])->name('crypto.index');

    Route::post('/crypto-transaction/store', [CryptoTransactionController::class, 'store'])->name('crypto-transaction.store');
});


require __DIR__ . '/auth.php';
