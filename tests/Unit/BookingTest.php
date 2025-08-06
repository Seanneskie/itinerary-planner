<?php

namespace Tests\Unit;

use App\Models\Booking;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BookingTest extends TestCase
{
    public function test_casts_dates_and_coordinates(): void
    {
        $booking = new Booking([
            'check_in' => '2024-05-10',
            'check_out' => '2024-05-15',
            'latitude' => '45.123',
            'longitude' => '-123.456',
        ]);

        $this->assertInstanceOf(Carbon::class, $booking->check_in);
        $this->assertInstanceOf(Carbon::class, $booking->check_out);
        $this->assertIsFloat($booking->latitude);
        $this->assertIsFloat($booking->longitude);
        $this->assertSame(45.123, $booking->latitude);
        $this->assertSame(-123.456, $booking->longitude);
    }
}

