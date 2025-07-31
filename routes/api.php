<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\CheckBearerToken;

Route::middleware(CheckBearerToken::class)->group(function () {
    Route::apiResource('transactions', TransactionController::class);
});
