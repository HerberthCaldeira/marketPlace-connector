<?php

declare(strict_types = 1);

use App\Domains\Task\Infra\Http\Controllers\StartTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');

Route::post('import-offer', StartTaskController::class)->name('import.offer');
