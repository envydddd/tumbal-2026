<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/webhook/midtrans', [PaymentController::class, 'midtransNotification'])->name('webhook.midtrans');