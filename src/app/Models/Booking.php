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
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function billiardTable(): BelongsTo
    {
        return $this->belongsTo(BilliardTable::class);
    }
}
