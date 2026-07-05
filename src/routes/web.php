<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BilliardBookingController;
use Livewire\Livewire;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\PaymentController;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Route::get('/api/webhook/midtrans', function () {
    return response()->json([
        'message' => 'Midtrans webhook endpoint is active',
    ], 200);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/
Route::get('/', [BilliardBookingController::class, 'index'])->name('billiard.index');
Route::get('/meja/{table}', [BilliardBookingController::class, 'showTable'])->name('billiard.table');
Route::post('/meja/{table}/booking', [BilliardBookingController::class, 'store'])->name('billiard.booking.store');
Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('payment.show');