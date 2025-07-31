<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::apiResource('transactions', TransactionController::class);



