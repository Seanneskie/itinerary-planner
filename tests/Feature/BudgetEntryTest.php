<?php

namespace Tests\Feature;

use App\Models\BudgetEntry;
use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_edit_own_budget_entry(): void
    {
        $user = User::factory()->create();

        $itinerary = Itinerary::create([
            'user_id' => $user->id,
            'title' => 'Sample Trip',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
        ]);

        $entry = BudgetEntry::create([
            'itinerary_id' => $itinerary->id,
            'description' => 'Lunch',
            'amount' => 25.00,
            'entry_date' => now()->toDateString(),
            'category' => 'Food',
        ]);

        $response = $this->actingAs($user)->get(route('budgets.edit', $entry->id));

        $response->assertStatus(200);
    }
}
