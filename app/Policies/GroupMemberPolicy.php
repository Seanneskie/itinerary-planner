<?php

namespace App\Policies;

use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupMemberPolicy
{
    use HandlesAuthorization;

    public function view(User $user, GroupMember $groupMember): bool
    {
        return $user->id === $groupMember->itinerary->user_id;
    }

    public function update(User $user, GroupMember $groupMember): bool
    {
        return $this->view($user, $groupMember);
    }

    public function delete(User $user, GroupMember $groupMember): bool
    {
        return $this->view($user, $groupMember);
    }
}
