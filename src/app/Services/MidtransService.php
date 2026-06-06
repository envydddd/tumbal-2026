<?php

namespace App\Services;

use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    public function createSnapTransaction(Booking $booking): array
    {
        $booking->load('billiardTable.floor');

        $orderId = 'BOOKING-' . $booking->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->amount,
            ],
            'customer_details' => [
                'first_name' => $booking->customer_name,
                'phone' => $booking->phone_number,
            ],
            'item_details' => [
                [
                    'id' => 'BILLIARD-' . $booking->id,
                    'price' => (int) $booking->amount,
                    'quantity' => 1,
                    'name' => 'Booking ' . ($booking->billiardTable->room_name ?: $booking->billiardTable->name),
                ],
            ],
            'enabled_payments' => [
                'qris',
                'gopay',
                'bank_transfer',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return [
            'order_id' => $orderId,
            'snap_token' => $snapToken,
        ];
    }
}