<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    public function event_type(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    public function event_location(): BelongsTo
    {
        return $this->belongsTo(EventLocation::class);
    }

    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime',
        'sequence' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
