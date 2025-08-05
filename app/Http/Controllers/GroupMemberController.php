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
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('group_members', 'public');
        }

        GroupMember::create($validated);

        return back()->with('success', 'Group member added.');
    }

    public function update(Request $request, GroupMember $groupMember)
    {
        $this->authorize('update', $groupMember);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('group_members', 'public');
        }

        $groupMember->update($validated);

        return back()->with('success', 'Group member updated.');
    }

    public function destroy(GroupMember $groupMember)
    {
        $this->authorize('delete', $groupMember);

        $groupMember->delete();

        return back()->with('success', 'Group member removed.');
    }
}
