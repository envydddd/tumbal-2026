<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'billiard_table_id',
        'booking_date',
        'start_time',
        'end_time',
        'customer_name',
        'phone_number',
        'payment_method',
        'status',
        'amount',
        'payment_status',
        'payment_reference',
        'payment_gateway',
        'qris_url',
        'payment_url',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_date' => 'date',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function billiardTable(): BelongsTo
    {
        return $this->belongsTo(BilliardTable::class);
    }
}
