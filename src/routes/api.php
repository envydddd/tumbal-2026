<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\BilliardTableApiController;

Route::post('/webhook/midtrans', [PaymentController::class, 'midtransNotification'])->name('webhook.midtrans');
Route::get('/meja', [BilliardTableApiController::class, 'index']);
Route::get('/meja/{id}', [BilliardTableApiController::class, 'show']);