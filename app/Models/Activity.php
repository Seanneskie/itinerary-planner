<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    //
    protected $fillable = [
        'itinerary_id',
        'title',
        'location',
        'note',
        'scheduled_at',
        'budget',
        'attire_color',
        'attire_note',
        'latitude',
        'longitude',
        'photo_path',
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

}
