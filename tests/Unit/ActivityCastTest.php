<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ActivityCastTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_attributes_are_casted(): void
    {
        $user = User::factory()->create();

        $itinerary = Itinerary::create([
            'user_id' => $user->id,
            'title' => 'Sample Trip',
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-02',
        ]);

        $activity = Activity::create([
            'itinerary_id' => $itinerary->id,
            'title' => 'Hiking',
            'scheduled_at' => '2024-01-01 10:00:00',
            'budget' => '123.45',
            'latitude' => '10.1234567',
            'longitude' => '20.7654321',
        ])->fresh();

        $this->assertInstanceOf(Carbon::class, $activity->scheduled_at);
        $this->assertSame('2024-01-01 10:00:00', $activity->scheduled_at->format('Y-m-d H:i:s'));

        $this->assertIsFloat($activity->budget);
        $this->assertSame(123.45, $activity->budget);

        $this->assertIsFloat($activity->latitude);
        $this->assertSame(10.1234567, $activity->latitude);

        $this->assertIsFloat($activity->longitude);
        $this->assertSame(20.7654321, $activity->longitude);
    }
}

