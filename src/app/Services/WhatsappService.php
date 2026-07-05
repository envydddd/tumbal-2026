<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public function sendToCustomer(Booking $booking): bool
    {
        $booking->loadMissing('billiardTable.floor');

        $message = $this->buildCustomerMessage($booking);

        return $this->sendMessage(
            target: $this->normalizePhoneNumber($booking->phone_number),
            message: $message
        );
    }

    public function sendToAdmin(Booking $booking): bool
    {
        $booking->loadMissing('billiardTable.floor');

        $adminNumber = config('services.fonnte.admin_number') ?: env('ADMIN_WHATSAPP_NUMBER');

        if (! $adminNumber) {
            Log::warning('Admin WhatsApp number is not configured.');

            return false;
        }

        $message = $this->buildAdminMessage($booking);

        return $this->sendMessage(
            target: $this->normalizePhoneNumber($adminNumber),
            message: $message
        );
    }

    private function sendMessage(string $target, string $message): bool
    {
        $token = config('services.fonnte.token') ?: env('FONNTE_TOKEN');

        if (! $token) {
            Log::warning('Fonnte token is not configured.');

            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);

        if (! $response->successful()) {
            Log::error('Failed to send WhatsApp message via Fonnte.', [
                'target' => $target,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        }

        return true;
    }

    private function buildCustomerMessage(Booking $booking): string
    {
        $tableName = $booking->billiardTable->room_name ?: $booking->billiardTable->name;
        $floorName = $booking->billiardTable->floor->name;

        return "Halo {$booking->customer_name}, pembayaran booking billiard kamu sudah berhasil.\n\n"
            . "Detail Booking:\n"
            . "Meja/Ruangan: {$tableName}\n"
            . "Lantai: {$floorName}\n"
            . "Tanggal: {$booking->booking_date->format('d-m-Y')}\n"
            . "Jam: " . date('H:i', strtotime($booking->start_time)) . " - " . date('H:i', strtotime($booking->end_time)) . "\n"
            . "Total: Rp " . number_format($booking->amount, 0, ',', '.') . "\n"
            . "Status Pembayaran: Lunas\n\n"
            . "Silakan datang sesuai jadwal booking. Terima kasih.";
    }

    private function buildAdminMessage(Booking $booking): string
    {
        $tableName = $booking->billiardTable->room_name ?: $booking->billiardTable->name;
        $floorName = $booking->billiardTable->floor->name;

        return "Notifikasi Booking Billiard Lunas\n\n"
            . "Nama Pelanggan: {$booking->customer_name}\n"
            . "No. HP: {$booking->phone_number}\n"
            . "Meja/Ruangan: {$tableName}\n"
            . "Lantai: {$floorName}\n"
            . "Tanggal: {$booking->booking_date->format('d-m-Y')}\n"
            . "Jam: " . date('H:i', strtotime($booking->start_time)) . " - " . date('H:i', strtotime($booking->end_time)) . "\n"
            . "Total: Rp " . number_format($booking->amount, 0, ',', '.') . "\n"
            . "Metode Pembayaran: Midtrans\n"
            . "Status: Lunas\n\n"
            . "Silakan cek dashboard admin untuk detail booking.";
    }

    private function normalizePhoneNumber(string $phoneNumber): string
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (str_starts_with($phoneNumber, '0')) {
            return '62' . substr($phoneNumber, 1);
        }

        if (str_starts_with($phoneNumber, '8')) {
            return '62' . $phoneNumber;
        }

        return $phoneNumber;
    }
}