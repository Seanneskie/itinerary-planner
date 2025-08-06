<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\BudgetEntry;

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

        $budget = $request->budget;

        if ($request->filled('budget_entry_id')) {
            $budgetEntry = BudgetEntry::where('itinerary_id', $request->itinerary_id)
                ->findOrFail($request->budget_entry_id);

            $budgetEntry->activity()->associate($activity);
            $budgetEntry->description = $request->title;
            $budgetEntry->entry_date = $request->scheduled_at;
            if (! is_null($budget)) {
                $budgetEntry->amount = $budget;
            }
            $budgetEntry->save();
        } elseif (! is_null($budget)) {
            $activity->budgetEntry()->create([
                'itinerary_id' => $request->itinerary_id,
                'description' => $request->title,
                'amount' => $budget,
                'entry_date' => $request->scheduled_at,
                'category' => 'Activity',
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
        $this->authorize('update', $activity);

        $validated = $request->validated();
        $budgetEntryId = $validated['budget_entry_id'] ?? null;
        $budget = $validated['budget'] ?? null;
        unset($validated['budget_entry_id'], $validated['budget']);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('activities', 'public');
        }

        $activity->update($validated);

        if ($budgetEntryId) {
            $budgetEntry = BudgetEntry::where('itinerary_id', $activity->itinerary_id)
                ->findOrFail($budgetEntryId);

            // detach previous entry if different
            if ($activity->budgetEntry && $activity->budgetEntry->id !== $budgetEntryId) {
                $activity->budgetEntry()->update(['activity_id' => null]);
            }

            $budgetEntry->activity()->associate($activity);
            $budgetEntry->description = $activity->title;
            $budgetEntry->entry_date = $activity->scheduled_at;
            if (! is_null($budget)) {
                $budgetEntry->amount = $budget;
            }
            $budgetEntry->save();
        } else {
            // detach any existing linked entry
            $activity->budgetEntry()->update(['activity_id' => null]);

            if (! is_null($budget)) {
                $activity->budgetEntry()->create([
                    'itinerary_id' => $activity->itinerary_id,
                    'description' => $activity->title,
                    'amount' => $budget,
                    'entry_date' => $activity->scheduled_at,
                    'category' => 'Activity',
                    'spent_amount' => 0,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Activity updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        $this->authorize('delete', $activity);

        $activity->budgetEntry()->update(['activity_id' => null]);
        $activity->delete();

        return redirect()->back()->with('success', 'Activity deleted.');
    }
}
