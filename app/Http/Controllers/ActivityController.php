<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\BudgetEntry;
use Illuminate\Support\Facades\Auth;
class ActivityController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request)
    {
        $data = [
            'itinerary_id' => $request->itinerary_id,
            'title' => $request->title,
            'location' => $request->location,
            'budget' => $request->budget,
            'attire_color' => $request->attire_color,
            'attire_note' => $request->attire_note,
            'note' => $request->note,
            'scheduled_at' => $request->scheduled_at,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('activities', 'public');
        }

        $activity = Activity::create($data);

        if ($request->filled('budget')) {
            $activity->budgetEntry()->create([
                'itinerary_id' => $request->itinerary_id,
                'description'  => $request->title,
                'amount'       => $request->budget,
                'entry_date'   => $request->scheduled_at,
                'category'     => 'Activity',
                'spent_amount' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Activity added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        if ($activity->itinerary->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('activities', 'public');
        }

        $activity->update($validated);

        if (!is_null($activity->budget)) {
            $activity->budgetEntry()->updateOrCreate([], [
                'itinerary_id' => $activity->itinerary_id,
                'description'  => $activity->title,
                'amount'       => $activity->budget,
                'entry_date'   => $activity->scheduled_at,
                'category'     => 'Activity',
            ]);
        } else {
            $activity->budgetEntry()->delete();
        }

        return redirect()->back()->with('success', 'Activity updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        if ($activity->itinerary->user_id !== Auth::id()) {
            abort(403);
        }

        $activity->budgetEntry()->delete();
        $activity->delete();

        return redirect()->back()->with('success', 'Activity deleted.');
    }
}
