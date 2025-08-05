<?php

namespace App\Policies;

use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItineraryPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Itinerary $itinerary): bool
    {
        return $user->id === $itinerary->user_id;
    }

    public function update(User $user, Itinerary $itinerary): bool
    {
        return $this->view($user, $itinerary);
    }

    public function delete(User $user, Itinerary $itinerary): bool
    {
        return $this->view($user, $itinerary);
    }
}
