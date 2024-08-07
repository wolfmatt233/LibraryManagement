<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HoldController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAdmin;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    //user: loans
    Route::get('/loans', [LoanController::class, 'index'])->name('loans');
    Route::post('/createLoan/{id}', [LoanController::class, 'createLoan'])->name('createLoan');
    Route::post('/removeLoan/{id}', [LoanController::class, 'removeLoan'])->name('removeLoan');

    //user: books
    Route::get('/books', [BookController::class, 'index'])->name('books');
    Route::get('/books/{id}', [BookController::class, 'getBook'])->name('getBook');

    //user: wishlist
    Route::get('/wishlist', [BookController::class, 'wishlist'])->name('wishlist');
    Route::post('/addWishlist/{id}', [BookController::class, 'addWishlist'])->name('addWishlist');
    Route::post('/removeWishlist/{id}', [BookController::class, 'removeWishlist'])->name('removeWishlist');

    //user: holds
    Route::post('/createHold/{id}', [HoldController::class, 'createHold'])->name('createHold');
    Route::post('/cancelHold/{id}', [HoldController::class, 'cancelHold'])->name('cancelHold');
});

Route::middleware(CheckAdmin::class)->group(function () {
    //admin: loans
    Route::get('/viewAll', [LoanController::class, 'viewAll'])->name('viewAll');
    Route::get('/editLoan/{id}', [LoanController::class, 'editLoan'])->name('editLoan');
    Route::put('/updateLoan/{id}', [LoanController::class, 'updateLoan'])->name('updateLoan');
    Route::delete('/deleteLoan/{id}', [LoanController::class, 'deleteLoan'])->name('deleteLoan');

    //admin: books
    Route::get('/editBook/{id}', [BookController::class, 'editBook'])->name('editBook');
    Route::put('/updateBook/{id}', [BookController::class, 'updateBook'])->name('updateBook');
    Route::get('/addBook', [BookController::class, 'addBook'])->name('addBook');
    Route::post('/createBook', [BookController::class, 'createBook'])->name('createBook');
    Route::delete('/deleteBook/{id}', [BookController::class, 'deleteBook'])->name('deleteBook');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
