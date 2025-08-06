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

    /**
     * Cast attributes to common types.
     */
    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}

