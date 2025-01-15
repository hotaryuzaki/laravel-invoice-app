<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('companies', CompanyController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('items', ItemController::class);
Route::apiResource('invoices', InvoiceController::class);
Route::apiResource('invoice-items', InvoiceItemController::class);
