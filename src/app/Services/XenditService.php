<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class XenditService
{
    public function createQris(Booking $booking): array
    {
        $referenceId = 'BOOKING-' . $booking->id . '-' . Str::random(8);

        /*
         * Catatan:
         * Endpoint dan payload Xendit bisa berubah.
         * Cocokkan lagi dengan dokumentasi resmi Xendit QRIS terbaru.
         */
        $response = Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
            ->post('https://api.xendit.co/qr_codes', [
                'reference_id' => $referenceId,
                'type' => 'DYNAMIC',
                'currency' => 'IDR',
                'amount' => $booking->amount,
            ]);

        if (! $response->successful()) {
            throw new \Exception('Gagal membuat QRIS: ' . $response->body());
        }

        return [
            'reference_id' => $referenceId,
            'response' => $response->json(),
        ];
    }
}