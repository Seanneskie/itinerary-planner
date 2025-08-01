<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $fillable = [
        'itinerary_id',
        'name',
        'notes',
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}
