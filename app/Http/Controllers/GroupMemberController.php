<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupMember;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;

class GroupMemberController extends Controller
{
    public function store(Request $request)
    {
        $itinerary = Itinerary::where('user_id', Auth::id())->findOrFail($request->itinerary_id);

        $validated = $request->validate([
            'itinerary_id' => 'required|exists:itineraries,id',
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        GroupMember::create($validated);

        return back()->with('success', 'Group member added.');
    }

    public function update(Request $request, GroupMember $groupMember)
    {
        if ($groupMember->itinerary->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $groupMember->update($validated);

        return back()->with('success', 'Group member updated.');
    }

    public function destroy(GroupMember $groupMember)
    {
        if ($groupMember->itinerary->user_id !== Auth::id()) {
            abort(403);
        }

        $groupMember->delete();

        return back()->with('success', 'Group member removed.');
    }
}
