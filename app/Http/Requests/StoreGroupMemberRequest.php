<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;

class StoreGroupMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        Itinerary::where('user_id', Auth::id())->findOrFail($this->itinerary_id);

        return [
            'itinerary_id' => 'required|exists:itineraries,id',
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ];
    }
}
