<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;

class StoreActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $itinerary = Itinerary::where('user_id', Auth::id())
            ->findOrFail($this->itinerary_id);

        return [
            'itinerary_id' => 'required|exists:itineraries,id',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'budget_entry_id' => 'nullable|exists:budget_entries,id',
            'attire_color' => 'nullable|string|max:255',
            'attire_note' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'scheduled_at' => ['required', 'date', 'after_or_equal:' . $itinerary->start_date, 'before_or_equal:' . $itinerary->end_date],
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048',
        ];
    }
}
