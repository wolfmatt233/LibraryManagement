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
});

// Route::get('/loans', function () {
//     return view('loans');
// })->middleware(['auth', 'verified'])->name('loans');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
