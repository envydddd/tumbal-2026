<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function show(Booking $booking, MidtransService $midtransService): View
    {
        $booking->load('billiardTable.floor');

        if (
            $booking->payment_method === 'transfer'
            && in_array($booking->payment_status, ['waiting_payment', 'menunggu_pembayaran'])
            && empty($booking->snap_token)
        ) {
            $transaction = $midtransService->createSnapTransaction($booking);

            $booking->update([
                'payment_gateway' => 'midtrans',
                'payment_reference' => $transaction['order_id'],
                'snap_token' => $transaction['snap_token'],
                'payment_status' => 'menunggu_pembayaran',
                'expired_at' => now()->addMinutes(30),
            ]);

            $booking->refresh();
        }

        return view('payment.show', [
            'booking' => $booking,
            'midtransClientKey' => config('midtrans.client_key'),
            'isProduction' => (bool) config('midtrans.is_production'),
        ]);
    }

    public function midtransNotification(Request $request)
    {
        Log::info('MIDTRANS WEBHOOK MASUK', $request->all());
        $serverKey = config('midtrans.server_key');

        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');

        $validSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $validSignature) {
            return response()->json([
                'message' => 'Invalid signature',
            ], 403);
        }

        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        $booking = Booking::where('payment_reference', $orderId)->first();

        if (! $booking) {
            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }

        if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
            $booking->update([
                'payment_status' => 'lunas',
                'status' => 'confirmed',
                'paid_at' => $booking->paid_at ?: now(),
            ]);

            $whatsappService = app(WhatsappService::class);

            if (! $booking->customer_whatsapp_sent_at) {
                try {
                    $sentToCustomer = $whatsappService->sendToCustomer($booking);

                    if ($sentToCustomer) {
                        $booking->update([
                            'customer_whatsapp_sent_at' => now(),
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::error('Failed to send WhatsApp notification to customer.', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if (! $booking->admin_whatsapp_sent_at) {
                try {
                    $sentToAdmin = $whatsappService->sendToAdmin($booking);

                    if ($sentToAdmin) {
                        $booking->update([
                            'admin_whatsapp_sent_at' => now(),
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::error('Failed to send WhatsApp notification to admin.', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        if ($transactionStatus === 'pending') {
            $booking->update([
                'payment_status' => 'menunggu_pembayaran',
            ]);
        }

        if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $booking->update([
                'payment_status' => 'gagal',
                'status' => 'cancelled',
            ]);
        }

        return response()->json([
            'message' => 'Notification processed',
        ]);
    }
}