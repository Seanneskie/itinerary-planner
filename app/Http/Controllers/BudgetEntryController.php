<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BudgetEntry;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;

class BudgetEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $itinerary)
    {
        $filter = $request->query('category');

        $itinerary = Itinerary::where('user_id', Auth::id())
            ->with(['budgetEntries' => function ($query) use ($filter) {
                $query->when($filter, fn ($q) => $q->where('category', $filter));
            }])
            ->findOrFail($itinerary);

        $categories = BudgetEntry::where('itinerary_id', $itinerary->id)
            ->pluck('category')
            ->filter()
            ->unique();

        return view('budgets.index', compact('itinerary', 'categories', 'filter'));
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

        $validated = $request->validate([
            'itinerary_id' => 'required|exists:itineraries,id',
            'description'  => 'required|string|max:255',
            'amount'       => 'required|numeric',
            'entry_date'   => 'required|date',
            'category'     => 'nullable|string|max:255',
        ]);

        BudgetEntry::create($validated);

        return back()->with('success', 'Entry added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BudgetEntry $budgetEntry)
    {
        if (!$budgetEntry->itinerary || $budgetEntry->itinerary->user_id !== Auth::id()) {
            abort(403);
        }

        return view('budgets.show', compact('budgetEntry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BudgetEntry $budgetEntry)
    {
        if (!$budgetEntry->itinerary || $budgetEntry->itinerary->user_id !== Auth::id()) {
            abort(403);
        }

        return view('budgets.edit', compact('budgetEntry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BudgetEntry $budgetEntry)
    {
        if (!$budgetEntry->itinerary || $budgetEntry->itinerary->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric',
            'entry_date'  => 'required|date',
            'category'    => 'nullable|string|max:255',
        ]);

        $budgetEntry->update($validated);

        return back()->with('success', 'Entry updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BudgetEntry $budgetEntry)
    {
        if (!$budgetEntry->itinerary || $budgetEntry->itinerary->user_id !== Auth::id()) {
            abort(403);
        }

        $budgetEntry->delete();

        return back()->with('success', 'Entry removed.');
    }
}
