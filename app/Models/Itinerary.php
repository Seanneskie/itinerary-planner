<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    // Allow mass assignment
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function groupMembers()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}