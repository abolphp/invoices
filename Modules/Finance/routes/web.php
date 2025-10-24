<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\app\Http\Controllers\InvoiceController;

Route::get('/invoice' , [invoiceController::class , "index" ] )->name('invoice');
Route::post('/generate' , [invoiceController::class , "generate" ] )->name('generate.pdf');
Route::get('/download/{file}' , [invoiceController::class , "download" ] )->name('invoice.download');

