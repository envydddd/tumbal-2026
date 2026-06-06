<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;

class WhatsappService
{
    public function sendPaymentSuccessMessage(Booking $booking): void
    {
        $booking->load('billiardTable.floor');

        $message = "Halo {$booking->customer_name}, pembayaran booking billiard kamu berhasil.\n\n"
            . "Meja: " . ($booking->billiardTable->room_name ?: $booking->billiardTable->name) . "\n"
            . "Lantai: {$booking->billiardTable->floor->name}\n"
            . "Tanggal: {$booking->booking_date->format('d-m-Y')}\n"
            . "Jam: " . \Carbon\Carbon::parse($booking->start_time)->format('H:i')
            . " - "
            . \Carbon\Carbon::parse($booking->end_time)->format('H:i') . "\n"
            . "Status: Confirmed\n\n"
            . "Terima kasih.";

        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target' => $booking->phone_number,
            'message' => $message,
        ]);
    }
}