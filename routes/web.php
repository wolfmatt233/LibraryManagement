<?php

use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// landing page -> no auth needed

//need auth:
//browse/search books
//loans
//profile
//wishlist

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [LoanController::class, 'view'])->name('dashboard');
    Route::get('/pastLoans', [LoanController::class, 'pastLoans'])->name('pastLoans');
    Route::get('/viewAll', [LoanController::class, 'viewAll'])->name('viewAll');
    Route::get('/editLoan/{id}', [LoanController::class, 'editLoan'])->name('editLoan');
    Route::put('/updateLoan/{id}', [LoanController::class, 'updateLoan'])->name('updateLoan');
    Route::delete('/removeLoan/{id}', [LoanController::class, 'removeLoan'])->name('removeLoan');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
