<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $itinerary = Itinerary::where('user_id', Auth::id())
            ->findOrFail($request->itinerary_id);

        $request->validate([
            'itinerary_id' => 'required|exists:itineraries,id',
            'place' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'check_in' => ['required', 'date', 'after_or_equal:' . $itinerary->start_date, 'before_or_equal:' . $itinerary->end_date],
            'check_out' => ['required', 'date', 'after_or_equal:check_in', 'before_or_equal:' . $itinerary->end_date],
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

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

    public function update(Request $request, Booking $booking)
    {
        if ($booking->itinerary->user_id !== Auth::id()) {
            abort(403);
        }

        $itinerary = $booking->itinerary;

        $validated = $request->validate([
            'place' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'check_in' => ['required', 'date', 'after_or_equal:' . $itinerary->start_date, 'before_or_equal:' . $itinerary->end_date],
            'check_out' => ['required', 'date', 'after_or_equal:check_in', 'before_or_equal:' . $itinerary->end_date],
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

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

