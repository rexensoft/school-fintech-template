<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/login', [AuthController::class, 'loginView'])->name('login.loginView');
Route::post('/login', [AuthController::class, 'login'])->name('login');


// WITH AUTHENTICATION
Route::middleware('auth')->group(function() {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // TRANSACTION
    Route::prefix('/transactions')->name('transactions.')->group(function() {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::post('/topup', [TransactionController::class, 'topup'])->name('topup');
    });

    // USER
    Route::prefix('/users')->name('users.')->group(function() {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{userId}', [UserController::class, 'update'])->name('update');
        Route::delete('/{userId}', [UserController::class, 'destroy'])->name('destroy');
    });
});