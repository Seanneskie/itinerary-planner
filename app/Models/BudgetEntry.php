<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetEntry extends Model
{
    protected $fillable = [
        'itinerary_id',
        'description',
        'amount',
        'spent_amount',
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
            'spent_amount' => 'float',
        ];
    }

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}
