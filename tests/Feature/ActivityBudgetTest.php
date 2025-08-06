<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityBudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_budget_entry_and_sets_budget_via_accessor(): void
    {
        $user = User::factory()->create();
        $itinerary = Itinerary::create([
            'user_id' => $user->id,
            'title' => 'Sample Trip',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
        ]);

        $scheduled = now()->addHour();

        $this->actingAs($user)->post(route('itineraries.activities.store', $itinerary->id), [
            'itinerary_id' => $itinerary->id,
            'title' => 'Hiking',
            'location' => 'Mountain',
            'budget' => 150,
            'scheduled_at' => $scheduled->toDateTimeString(),
        ]);

        $activity = Activity::first();
        $this->assertNotNull($activity->budgetEntry);
        $this->assertEquals(150, $activity->budget);
    }

    public function test_update_changes_linked_budget_entry_amount(): void
    {
        $user = User::factory()->create();
        $itinerary = Itinerary::create([
            'user_id' => $user->id,
            'title' => 'Sample Trip',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
        ]);

        $activity = Activity::create([
            'itinerary_id' => $itinerary->id,
            'title' => 'Hiking',
            'scheduled_at' => now()->addHour(),
        ]);

        $activity->budgetEntry()->create([
            'itinerary_id' => $itinerary->id,
            'description' => 'Hiking',
            'amount' => 100,
            'entry_date' => now()->toDateString(),
            'category' => 'Activity',
            'spent_amount' => 0,
        ]);

        $this->actingAs($user)->patch(route('activities.update', $activity->id), [
            'title' => 'Hiking',
            'location' => 'Mountain',
            'budget' => 200,
            'scheduled_at' => now()->addHour()->toDateTimeString(),
        ]);

        $this->assertEquals(200, $activity->budgetEntry->fresh()->amount);
        $this->assertEquals(200, $activity->fresh()->budget);
    }
}
