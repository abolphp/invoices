<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\app\Http\Controllers\InvoiceController;


Route::group([
    'middleware' => 'web',
], function ($router) {
    Route::get('/invoice', [InvoiceController::class , "index"])->name('invoice');
    Route::post('/generate', [InvoiceController::class , "generate"])->name('generate.pdf');
    Route::get('/download/{file}', [InvoiceController::class , "download"])->name('invoice.download');
});


