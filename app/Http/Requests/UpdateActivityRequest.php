<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
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
        $activity = $this->route('activity');
        $itinerary = $activity->itinerary;

        return [
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'attire_color' => 'nullable|string|max:255',
            'attire_note' => 'nullable|string|max:255',
            'scheduled_at' => ['required', 'date', 'after_or_equal:' . $itinerary->start_date, 'before_or_equal:' . $itinerary->end_date],
            'note' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048',
        ];
    }
}
