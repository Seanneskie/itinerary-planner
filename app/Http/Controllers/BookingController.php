<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request)
    {
        Booking::create([
            'itinerary_id' => $request->itinerary_id,
            'place' => $request->place,
            'location' => $request->location,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->back()->with('success', 'Booking added.');
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        if ($booking->itinerary->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        $booking->update($validated);

        return redirect()->back()->with('success', 'Booking updated.');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->itinerary->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->delete();

        return redirect()->back()->with('success', 'Booking deleted.');
    }
}

