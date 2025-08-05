<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupMemberRequest;
use App\Http\Requests\UpdateGroupMemberRequest;
use App\Models\GroupMember;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;

class GroupMemberController extends Controller
{
    public function store(StoreGroupMemberRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('group_members', 'public');
        }

        GroupMember::create($validated);

        return back()->with('success', 'Group member added.');
    }

    public function update(UpdateGroupMemberRequest $request, GroupMember $groupMember)
    {
        $this->authorize('update', $groupMember);

        $validated = $request->validated();

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
