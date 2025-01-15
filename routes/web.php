<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Define a new API route
Route::get('/api/new-api-route', function () {
    return response()->json(['message' => 'This is a new API route!']);
});

Route::prefix('api')->group(function () {
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('items', ItemController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('invoice-items', InvoiceItemController::class);
});

require __DIR__.'/auth.php';
