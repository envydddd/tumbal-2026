<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(Booking $booking): View
    {
        $booking->load('billiardTable.floor');

        return view('payment.show', [
            'booking' => $booking,
        ]);
    }

    public function xenditWebhook(Request $request)
    {
        $callbackToken = $request->header('x-callback-token');

        if ($callbackToken !== env('XENDIT_WEBHOOK_TOKEN')) {
            return response()->json([
                'message' => 'Invalid callback token',
            ], 403);
        }

        $referenceId = $request->input('reference_id');
        $status = $request->input('status');

        $booking = Booking::where('payment_reference', $referenceId)->first();

        if (! $booking) {
            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }

        if (in_array($status, ['SUCCEEDED', 'COMPLETED', 'PAID'])) {
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'paid_at' => now(),
            ]);

            try {
                app(\App\Services\WhatsappService::class)
                    ->sendPaymentSuccessMessage($booking);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return response()->json([
            'message' => 'Webhook received',
        ]);
    }
}