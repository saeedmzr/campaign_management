<?php

use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::post("webhook", [\App\Http\Controllers\Api\BotController::class, "webhook"]);
