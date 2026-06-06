<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floor extends Model
{
    protected $fillable = [
        'name',
        'floor_number',
        'description',
        'table_specification',
        'cue_specification',
        'is_vip',
        'is_active',
    ];

    protected $casts = [
        'is_vip' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function billiardTables(): HasMany
    {
        return $this->hasMany(BilliardTable::class);
    }
}
