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
        Route::post('/withdraw', [TransactionController::class, 'withdraw'])->name('withdraw');
    });

    
    /*
        ADMIN ONLY
    */
    Route::middleware(['admin'])->group(function() {
        // USER
        Route::prefix('/users')->name('users.')->group(function() {
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::post('/import', [UserController::class, 'import'])->name('import');
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

        // TRANSACTION
        Route::prefix('/transactions')->name('transactions.')->group(function() {
            Route::prefix('/{transactionId}')->group(function() {
                Route::get('/approve-buy', [TransactionController::class, 'approveBuy'])->name('approveBuy');
                Route::get('/reject-buy', [TransactionController::class, 'rejectBuy'])->name('rejectBuy');
            });
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
        STUDENT ONLY
    */
    Route::middleware(['student'])->group(function() {
        // CART
        Route::prefix('/carts')->name('carts.')->group(function() {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
            Route::delete('/{itemId}', [CartController::class, 'destroy'])->name('destroy');
        });
        
        // STORE
        Route::prefix('/stores')->name('stores.')->group(function() {
            Route::get('/', [ItemController::class, 'index'])->name('index');
            Route::post('/{itemId}/add-cart', [CartController::class, 'store'])->name('store');
        });
    });


    /*
        ADMIN, TELLER ONLY
    */
    Route::middleware(['admin.teller'])->group(function() {
        // USER
        Route::prefix('/users')->name('users.')->group(function() {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/export', [UserController::class, 'export'])->name('export');
         });
    });


    /*
        ADMIN, STUDENT, TELLER ONLY
    */
    Route::middleware(['admin.student.teller'])->group(function() {
        // TRANSACTION
        Route::prefix('/transactions')->name('transactions.')->group(function() {
            Route::get('/export', [TransactionController::class, 'export'])->name('export');
            Route::post('/topup', [TransactionController::class, 'topup'])->name('topup');
        });
    });


    /*
        SELLER, STUDENT ONLY
    */
    Route::middleware(['seller.student'])->group(function() {
        // TRANSACTION
        Route::prefix('/transactions')->name('transactions.')->group(function() {
            Route::post('/{transactionId}/cancel', [TransactionController::class, 'cancel'])->name('cancel');
        });
    });
});