<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/invoice' , [invoiceController::class , "index" ] )->name('invoice');
Route::post('/generate' , [invoiceController::class , "generate" ] )->name('generate.pdf');
Route::get('/download/{file}' , [invoiceController::class , "download" ] )->name('invoice.download');

