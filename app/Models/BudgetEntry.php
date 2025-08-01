<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetEntry extends Model
{
    protected $fillable = [
        'itinerary_id',
        'description',
        'amount',
        'entry_date',
        'category',
    ];

    /**
     * Cast attributes to common types.
     */
    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'amount' => 'float',
        ];
    }

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}
