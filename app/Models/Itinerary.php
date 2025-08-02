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
        'photo_path',
    ];

    /**
     * Cast attributes to common types.
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function groupMembers()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function budgetEntries()
    {
        return $this->hasMany(BudgetEntry::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}