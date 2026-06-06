<?php

namespace App\Http\Controllers;

use App\Models\BilliardTable;
use App\Models\Booking;
use App\Models\Floor;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BilliardBookingController extends Controller
{
    public function index(): View
    {
        $floors = Floor::query()
            ->where('is_active', true)
            ->with(['billiardTables' => fn ($query) => $query->where('is_active', true)->orderBy('name')])
            ->orderBy('floor_number')
            ->get();

        return view('billiard.index', compact('floors'));
    }

    public function showTable(Request $request, BilliardTable $table): View
    {
        abort_unless($table->is_active, 404);

        $date = $request->query('date', now()->toDateString());

        $bookings = Booking::query()
            ->where('billiard_table_id', $table->id)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->keyBy(fn (Booking $booking) => Carbon::parse($booking->start_time)->format('H:i'));

        $slots = collect(range(10, 23))->map(function (int $hour) use ($bookings) {
            $start = sprintf('%02d:00', $hour);
            $end = sprintf('%02d:00', $hour + 1);
            $booking = $bookings->get($start);

            return [
                'start' => $start,
                'end' => $end,
                'label' => $start . ' - ' . $end,
                'is_booked' => $booking !== null,
                'booking' => $booking,
            ];
        });

        $table->load('floor');

        return view('billiard.table', compact('table', 'date', 'slots'));
    }

    public function store(Request $request, BilliardTable $table): RedirectResponse
    {
        abort_unless($table->is_active, 404);

        $validated = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'customer_name' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'max:30'],
            'payment_method' => ['required', 'in:cash,transfer'],
        ]);

        $slotTaken = Booking::query()
            ->where('billiard_table_id', $table->id)
            ->whereDate('booking_date', $validated['booking_date'])
            ->where('start_time', $validated['start_time'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($slotTaken) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Slot jam ini sudah dibooking. Pilih jam lain.']);
        }

        Booking::create([
            'billiard_table_id' => $table->id,
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'customer_name' => $validated['customer_name'],
            'phone_number' => $validated['phone_number'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('billiard.table', ['table' => $table, 'date' => $validated['booking_date']])
            ->with('success', 'Booking berhasil dibuat. Status masih pending sampai dikonfirmasi admin.');
    }
}
