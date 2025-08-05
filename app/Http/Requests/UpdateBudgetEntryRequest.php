<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBudgetEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $budgetEntry = $this->route('budgetEntry');
        $itineraryId = $budgetEntry->itinerary->id;

        return [
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'entry_date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'participants' => 'array|nullable',
            'participants.*' => [
                'integer',
                Rule::exists('group_members', 'id')->where('itinerary_id', $itineraryId),
            ],
            'paid_participants' => 'array|nullable',
            'paid_participants.*' => [
                'integer',
                Rule::exists('group_members', 'id')->where('itinerary_id', $itineraryId),
            ],
        ];
    }
}
