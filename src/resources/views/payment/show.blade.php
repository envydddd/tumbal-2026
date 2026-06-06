<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Booking</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e5e7eb;
        }

        .container {
            max-width: 760px;
            margin: 0 auto;
            padding: 32px 20px;
        }

        .card {
            background: #111827;
            border: 1px solid #334155;
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #bbf7d0;
        }

        .qris-box {
            background: #f8fafc;
            color: #0f172a;
            border-radius: 18px;
            padding: 24px;
            text-align: center;
        }

        .dummy-qris {
            width: 240px;
            height: 240px;
            margin: 20px auto;
            background:
                linear-gradient(90deg, #111827 10px, transparent 10px) 0 0 / 30px 30px,
                linear-gradient(#111827 10px, transparent 10px) 0 0 / 30px 30px,
                #ffffff;
            border: 12px solid #ffffff;
            box-shadow: 0 0 0 4px #111827;
        }

        .status {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 999px;
            background: #78350f;
            color: #fde68a;
            font-size: 14px;
            font-weight: bold;
        }

        a {
            color: #93c5fd;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <p>
        <a href="{{ route('billiard.index') }}">← Kembali ke halaman utama</a>
    </p>

    <div class="card">
        <h1>Pembayaran Booking</h1>

        <p>
            Meja:
            <strong>
                {{ $booking->billiardTable->room_name ?: $booking->billiardTable->name }}
            </strong>
        </p>

        <p>
            Lantai:
            <strong>{{ $booking->billiardTable->floor->name }}</strong>
        </p>

        <p>
            Tanggal:
            <strong>{{ $booking->booking_date->format('d-m-Y') }}</strong>
        </p>

        <p>
            Jam:
            <strong>
                {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                -
                {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
            </strong>
        </p>

        <p>
            Nama:
            <strong>{{ $booking->customer_name }}</strong>
        </p>

        <p>
            Nomor WhatsApp:
            <strong>{{ $booking->phone_number }}</strong>
        </p>

        <p>Total pembayaran:</p>

        <div class="amount">
            Rp {{ number_format($booking->amount, 0, ',', '.') }}
        </div>

        <p style="margin-top: 16px;">
            Status:
            <span class="status">{{ $booking->payment_status }}</span>
        </p>
    </div>

    <div class="card qris-box">
        <h2>Scan QRIS</h2>

        @if ($booking->qris_url)
            <img src="{{ $booking->qris_url }}" alt="QRIS" style="max-width: 280px; width: 100%;">
        @else
            <div class="dummy-qris"></div>
            <p><strong>QRIS belum terhubung ke payment gateway.</strong></p>
        @endif

        <p>
            Scan menggunakan DANA, OVO, GoPay, ShopeePay, atau Mobile Banking.
        </p>
    </div>
</div>
</body>
</html>