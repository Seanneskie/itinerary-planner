<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'itinerary_id',
        'place',
        'location',
        'check_in',
        'check_out',
        'latitude',
        'longitude',
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}

