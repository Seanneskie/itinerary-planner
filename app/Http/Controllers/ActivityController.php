<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\BudgetEntry;
use Illuminate\Support\Facades\Auth;
use App\Models\Itinerary;
class ActivityController extends Controller
{
    protected $fillable = ['title', 'note', 'scheduled_at', 'location', 'latitude', 'longitude', 'photo_path', 'itinerary_id'];

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
    public function store(Request $request)
    {
        $itinerary = Itinerary::where('user_id', Auth::id())
            ->findOrFail($request->itinerary_id);

        $request->validate([
            'itinerary_id' => 'required|exists:itineraries,id',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'attire_color' => 'nullable|string|max:255',
            'attire_note' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'scheduled_at' => ['required', 'date', 'after_or_equal:' . $itinerary->start_date, 'before_or_equal:' . $itinerary->end_date],
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048',
        ]);

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
    public function update(Request $request, Activity $activity)
    {
        $this->authorize('update', $activity);

        $itinerary = $activity->itinerary;

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'location'     => 'nullable|string|max:255',
            'budget'       => 'nullable|numeric|min:0',
            'attire_color' => 'nullable|string|max:255',
            'attire_note'  => 'nullable|string|max:255',
            'scheduled_at' => ['required', 'date', 'after_or_equal:' . $itinerary->start_date, 'before_or_equal:' . $itinerary->end_date],
            'note'         => 'nullable|string',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'photo'        => 'nullable|image|max:2048',
        ]);

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
        $this->authorize('delete', $activity);

        $activity->budgetEntry()->delete();
        $activity->delete();

        return redirect()->back()->with('success', 'Activity deleted.');
    }
}
