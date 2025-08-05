<?php

namespace App\Policies;

use App\Models\BudgetEntry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BudgetEntryPolicy
{
    use HandlesAuthorization;

    protected function owns(User $user, BudgetEntry $budgetEntry): bool
    {
        return $budgetEntry->itinerary && $budgetEntry->itinerary->user_id === $user->id;
    }

    public function view(User $user, BudgetEntry $budgetEntry): bool
    {
        return $this->owns($user, $budgetEntry);
    }

    public function update(User $user, BudgetEntry $budgetEntry): bool
    {
        return $this->owns($user, $budgetEntry);
    }

    public function delete(User $user, BudgetEntry $budgetEntry): bool
    {
        return $this->owns($user, $budgetEntry);
    }
}
