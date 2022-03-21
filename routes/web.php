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
    });

    
    /*
        ADMIN ONLY
    */
    Route::middleware(['admin'])->group(function() {
        // USER
        Route::prefix('/users')->name('users.')->group(function() {
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::put('/{userId}', [UserController::class, 'update'])->name('update');
            Route::delete('/{userId}', [UserController::class, 'destroy'])->name('destroy');
        });
    });


    /*
        SELLER ONLY
    */
    Route::middleware(['seller'])->group(function() {
        // ITEM
        Route::prefix('/items')->name('items.')->group(function() {
            Route::get('/', [ItemController::class, 'index'])->name('index');
            Route::post('/', [ItemController::class, 'store'])->name('store');
            Route::put('/{itemId}', [ItemController::class, 'update'])->name('update');
            Route::delete('/{itemId}', [ItemController::class, 'destroy'])->name('destroy');
        });
    });


    /*
        TELLER ONLY
    */
    Route::middleware(['teller'])->group(function() {
        // TRANSACTION
        Route::prefix('/transactions')->name('transactions.')->group(function() {
            Route::prefix('/{transactionId}')->group(function() {
                Route::get('/approve', [TransactionController::class, 'approve'])->name('approve');
                Route::get('/reject', [TransactionController::class, 'reject'])->name('reject');
            });
        });
    });


    /*
        ADMIN, STUDENT, TELLER ONLY
    */
    Route::middleware(['admin.teller'])->group(function() {
        // USER
        Route::prefix('/users')->name('users.')->group(function() {
            Route::get('/', [UserController::class, 'index'])->name('index');
         });
    });


    /*
        ADMIN, STUDENT, TELLER ONLY
    */
    Route::middleware(['admin.student.teller'])->group(function() {
        // TRANSACTION
        Route::prefix('/transactions')->name('transactions.')->group(function() {
            Route::post('/topup', [TransactionController::class, 'topup'])->name('topup');
        });
    });
});