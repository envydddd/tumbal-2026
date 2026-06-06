<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking {{ $table->name }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #0f172a; color: #e5e7eb; }
        .container { max-width: 900px; margin: 0 auto; padding: 32px 20px; }
        a { color: #93c5fd; text-decoration: none; }
        .card { background: #111827; border: 1px solid #334155; border-radius: 18px; padding: 22px; margin-bottom: 20px; }
        h1 { margin: 0 0 8px; }
        .muted { color: #94a3b8; }
        .date-form { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px; }
        input, select, button { padding: 12px; border-radius: 10px; border: 1px solid #475569; background: #020617; color: #e5e7eb; }
        button { cursor: pointer; background: #2563eb; border-color: #2563eb; font-weight: bold; }
        .slot { display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: center; padding: 14px; border-radius: 14px; border: 1px solid #334155; background: #020617; margin-bottom: 10px; }
        .booked { border-color: #7f1d1d; background: #1f1111; }
        .available { border-color: #14532d; }
        .status { padding: 7px 10px; border-radius: 999px; font-size: 13px; }
        .status-booked { background: #7f1d1d; color: #fecaca; }
        .status-free { background: #14532d; color: #bbf7d0; }
        .booking-form { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-top: 12px; }
        .booking-form .full { grid-column: 1 / -1; }
        .alert-success { background: #064e3b; border: 1px solid #10b981; padding: 12px; border-radius: 12px; margin-bottom: 16px; }
        .alert-error { background: #7f1d1d; border: 1px solid #ef4444; padding: 12px; border-radius: 12px; margin-bottom: 16px; }
        @media (max-width: 700px) { .slot, .booking-form { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="container">
    <p><a href="{{ route('billiard.index') }}">← Kembali pilih lantai</a></p>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card">
        <h1>{{ $table->room_name ?: $table->name }}</h1>
        <p class="muted">{{ $table->floor->name }} — {{ $table->description ?: 'Pilih slot jam yang masih tersedia.' }}</p>

        <form class="date-form" method="GET" action="{{ route('billiard.table', $table) }}">
            <input type="date" name="date" value="{{ $date }}" min="{{ now()->toDateString() }}">
            <button type="submit">Cek Jadwal</button>
        </form>
    </div>

    <div class="card">
        <h2>Jadwal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</h2>

        @foreach ($slots as $slot)
            <div class="slot {{ $slot['is_booked'] ? 'booked' : 'available' }}">
                <div>
                    <strong>{{ $slot['label'] }}</strong>
                    <div class="muted">
                        @if ($slot['is_booked'])
                            Sudah dibooking oleh {{ $slot['booking']->customer_name }}
                        @else
                            Tersedia untuk dipesan
                        @endif
                    </div>
                </div>
                <span class="status {{ $slot['is_booked'] ? 'status-booked' : 'status-free' }}">
                    {{ $slot['is_booked'] ? 'Booked' : 'Available' }}
                </span>

                @unless ($slot['is_booked'])
                    <form class="booking-form full" method="POST" action="{{ route('billiard.booking.store', $table) }}">
                        @csrf
                        <input type="hidden" name="booking_date" value="{{ $date }}">
                        <input type="hidden" name="start_time" value="{{ $slot['start'] }}">
                        <input type="hidden" name="end_time" value="{{ $slot['end'] }}">
                        <input type="text" name="customer_name" placeholder="Nama pembeli" required>
                        <input type="text" name="phone_number" placeholder="Nomor telepon" required>
                        <select name="payment_method" required>
                            <option value="cash">Offline / Cash</option>
                            <option value="transfer">Cashless / Transfer</option>
                        </select>
                        <button type="submit">Booking Jam Ini</button>
                    </form>
                @endunless
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
