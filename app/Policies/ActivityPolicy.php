<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Activity $activity): bool
    {
        return $user->id === $activity->itinerary->user_id;
    }

    public function update(User $user, Activity $activity): bool
    {
        return $this->view($user, $activity);
    }

    public function delete(User $user, Activity $activity): bool
    {
        return $this->view($user, $activity);
    }
}
