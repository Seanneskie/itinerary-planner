<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreBudgetEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $itinerary = Itinerary::where('user_id', Auth::id())
            ->with('groupMembers')
            ->findOrFail($this->itinerary_id);

        return [
            'itinerary_id' => 'required|exists:itineraries,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'entry_date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'participants' => 'array|nullable',
            'participants.*' => [
                'integer',
                Rule::exists('group_members', 'id')->where('itinerary_id', $itinerary->id),
            ],
            'paid_participants' => 'array|nullable',
            'paid_participants.*' => [
                'integer',
                Rule::exists('group_members', 'id')->where('itinerary_id', $itinerary->id),
            ],
        ];
    }
}
