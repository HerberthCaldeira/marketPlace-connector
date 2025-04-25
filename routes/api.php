<?php

declare(strict_types = 1);

use App\Http\Controllers\ImportOfferController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');

Route::post('import-offer', ImportOfferController::class)->name('import.offer');
