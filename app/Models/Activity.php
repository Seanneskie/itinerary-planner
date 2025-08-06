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
        'attire_color',
        'attire_note',
        'latitude',
        'longitude',
        'photo_path',
    ];


    /**
     * Cast attributes to common types.
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'budget' => 'float',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function budgetEntry()
    {
        return $this->hasOne(BudgetEntry::class);
    }

    public function getBudgetAttribute()
    {
        return $this->budgetEntry->amount ?? null;
    }
}
