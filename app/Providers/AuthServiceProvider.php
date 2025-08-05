<?php

namespace App\Providers;

use App\Models\{Activity, BudgetEntry, Booking, GroupMember, Itinerary};
use App\Policies\{ActivityPolicy, BudgetEntryPolicy, BookingPolicy, GroupMemberPolicy, ItineraryPolicy};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Activity::class => ActivityPolicy::class,
        BudgetEntry::class => BudgetEntryPolicy::class,
        Booking::class => BookingPolicy::class,
        GroupMember::class => GroupMemberPolicy::class,
        Itinerary::class => ItineraryPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
