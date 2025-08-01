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

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}
