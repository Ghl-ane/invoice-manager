<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('clients', ClientController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/send',       [InvoiceController::class, 'send'])->name('invoices.send');
    Route::get('invoices/{invoice}/share-link',  [InvoiceController::class, 'shareLink'])->name('invoices.share-link');
    Route::get('invoices/{invoice}/pdf',         [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
});

// Public invoice view — no auth, but URL must carry a valid signature
Route::get('invoices/{invoice}/view', [InvoiceController::class, 'shared'])
    ->name('invoices.shared')
    ->middleware('signed');

require __DIR__.'/auth.php';