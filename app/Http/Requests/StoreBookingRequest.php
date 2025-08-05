<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $itinerary = Itinerary::where('user_id', Auth::id())
            ->findOrFail($this->itinerary_id);

        return [
            'itinerary_id' => 'required|exists:itineraries,id',
            'place' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'check_in' => ['required', 'date', 'after_or_equal:' . $itinerary->start_date, 'before_or_equal:' . $itinerary->end_date],
            'check_out' => ['required', 'date', 'after_or_equal:check_in', 'before_or_equal:' . $itinerary->end_date],
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];
    }
}
