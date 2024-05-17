<?php

use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::post("/orders", [OrderController::class, 'store']);
Route::get("/orders", [OrderController::class, 'show']);
Route::get("/currencies", [CurrencyController::class, 'index']);
Route::get("/rates", [CurrencyController::class, 'rate']);
