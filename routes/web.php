<?php

use App\Http\Controllers\BookController;
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
    //user: loans
    Route::get('/dashboard', [LoanController::class, 'index'])->name('dashboard');
    Route::get('/pastLoans', [LoanController::class, 'pastLoans'])->name('pastLoans');
    Route::post('/createLoan/{id}', [LoanController::class, 'createLoan'])->name('createLoan');
    Route::post('/removeLoan/{id}', [LoanController::class, 'removeLoan'])->name('removeLoan');

    //user: books
    Route::get('/books', [BookController::class, 'index'])->name('books');
    Route::get('/books/{id}', [BookController::class, 'getBook'])->name('getBook');
    Route::post('/addWishlist/{id}', [BookController::class, 'addWishlist'])->name('addWishlist');

    //user: holds
    //TO DO
    Route::get('/holds', [BookController::class, 'index'])->name('holds');
    Route::post('/createHold/{id}', [BookController::class, 'createHold'])->name('createHold');

    //admin: loans
    Route::get('/viewAll', [LoanController::class, 'viewAll'])->name('viewAll');
    Route::get('/editLoan/{id}', [LoanController::class, 'editLoan'])->name('editLoan');
    Route::put('/updateLoan/{id}', [LoanController::class, 'updateLoan'])->name('updateLoan');
    Route::delete('/deleteLoan/{id}', [LoanController::class, 'deleteLoan'])->name('deleteLoan');

    //admin: books
    Route::get('/addBook', [BookController::class, 'addBook'])->name('addBook');
    Route::post('/createBook', [BookController::class, 'createBook'])->name('createBook');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
