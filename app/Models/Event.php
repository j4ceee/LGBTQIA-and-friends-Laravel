<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    use HasFactory;

    public function event_type(): HasOne
    {
        return $this->hasOne(Event_Type::class);
    }

    public function event_location(): HasOne {
        return $this->hasOne(Event_Location::class);
    }
}
