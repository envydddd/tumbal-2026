<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/webhook/xendit/qris', [PaymentController::class, 'xenditWebhook'])->name('webhook.xendit.qris');