<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\EbookController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\EbookReaderController;

/*
|--------------------------------------------------------------------------
| Public Routes (Library)
|--------------------------------------------------------------------------
*/
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');

// Borrowing (no login required)
Route::get('/borrow/{book}', [BorrowController::class, 'create'])->name('borrow.create');
Route::post('/borrow/{book}', [BorrowController::class, 'store'])->name('borrow.store');
Route::get('/borrow/{loan}/success', [BorrowController::class, 'success'])->name('borrow.success');

// Ebook reader (no login required)
Route::get('/read/{book}', [EbookReaderController::class, 'show'])->name('ebook.reader');
Route::get('/read/{book}/stream', [EbookReaderController::class, 'stream'])->name('ebook.stream');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', UserController::class)->except(['show']);

    // App Settings
    Route::get('/settings', [AppSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AppSettingController::class, 'update'])->name('settings.update');

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Physical Books
    Route::resource('books', BookController::class);

    // E-Books
    Route::resource('ebooks', EbookController::class)->parameters(['ebooks' => 'book']);

    // Loans
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::patch('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');
    Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');

    // Import & Export
    Route::prefix('import-export')->name('io.')->group(function () {
        // Exports
        Route::get('/export/categories', [ImportExportController::class, 'exportCategories'])->name('export.categories');
        Route::get('/export/books', [ImportExportController::class, 'exportBooks'])->name('export.books');
        Route::get('/export/ebooks', [ImportExportController::class, 'exportEbooks'])->name('export.ebooks');
        Route::get('/export/loans', [ImportExportController::class, 'exportLoans'])->name('export.loans');

        // Imports
        Route::post('/import/categories', [ImportExportController::class, 'importCategories'])->name('import.categories');
        Route::post('/import/books', [ImportExportController::class, 'importBooks'])->name('import.books');
        Route::post('/import/ebooks', [ImportExportController::class, 'importEbooks'])->name('import.ebooks');
        Route::post('/import/loans', [ImportExportController::class, 'importLoans'])->name('import.loans');

        // Templates
        Route::get('/template/{type}', [ImportExportController::class, 'downloadTemplate'])->name('template');
    });
});
