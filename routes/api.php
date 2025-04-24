<?php

use App\Http\Controllers\ImportOfferController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('import-offer', ImportOfferController::class)->name('import.offer');
