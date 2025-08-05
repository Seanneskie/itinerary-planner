<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetEntryRequest;
use App\Http\Requests\UpdateBudgetEntryRequest;
use App\Models\BudgetEntry;
use App\Models\Itinerary;
use Illuminate\Http\Request;
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
            ->with('groupMembers')
            ->findOrFail($itinerary);

        $budgetEntries = BudgetEntry::where('itinerary_id', $itinerary->id)
            ->when($filter, fn ($q) => $q->where('category', $filter))
            ->paginate(10)
            ->withQueryString();

        $categories = BudgetEntry::where('itinerary_id', $itinerary->id)
            ->pluck('category')
            ->filter()
            ->unique();

        return view('budgets.index', compact('itinerary', 'categories', 'filter', 'budgetEntries'));
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
    public function store(StoreBudgetEntryRequest $request)
    {
        $validated = $request->validated();

        $validated['spent_amount'] = 0;
        $validated['participants'] = $request->input('participants', []);
        $validated['paid_participants'] = $request->input('paid_participants', []);

        BudgetEntry::create($validated);

        return back()->with('success', 'Entry added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BudgetEntry $budgetEntry)
    {
        if (! $budgetEntry->itinerary || (int) $budgetEntry->itinerary->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $budgetEntry->load('itinerary.groupMembers');

        return view('budgets.show', compact('budgetEntry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BudgetEntry $budgetEntry)
    {
        if (! $budgetEntry->itinerary || (int) $budgetEntry->itinerary->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $budgetEntry->load('itinerary.groupMembers');

        return view('budgets.edit', compact('budgetEntry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBudgetEntryRequest $request, BudgetEntry $budgetEntry)
    {
        if (! $budgetEntry->itinerary || (int) $budgetEntry->itinerary->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        $validated['participants'] = $request->input('participants', []);
        $validated['paid_participants'] = $request->input('paid_participants', []);

        $budgetEntry->update($validated);

        return back()->with('success', 'Entry updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BudgetEntry $budgetEntry)
    {
        if (! $budgetEntry->itinerary || (int) $budgetEntry->itinerary->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $budgetEntry->delete();

        return back()->with('success', 'Entry removed.');
    }

    public function editSpent(BudgetEntry $budgetEntry)
    {
        if (! $budgetEntry->itinerary || (int) $budgetEntry->itinerary->user_id !== (int) Auth::id()) {
            abort(403);
        }

        return view('budgets.edit-spent', compact('budgetEntry'));
    }

    public function updateSpent(Request $request, BudgetEntry $budgetEntry)
    {
        if (! $budgetEntry->itinerary || (int) $budgetEntry->itinerary->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'spent_amount' => 'required|numeric|min:0',
        ]);

        $budgetEntry->update($validated);

        return back()->with('success', 'Spent amount updated.');
    }

    public function togglePaid(Request $request, BudgetEntry $budgetEntry, int $member)
    {
        if (! $budgetEntry->itinerary || (int) $budgetEntry->itinerary->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $paid = $budgetEntry->paid_participants ?? [];

        if (in_array($member, $paid)) {
            $paid = array_values(array_diff($paid, [$member]));
        } else {
            $paid[] = $member;
        }

        $budgetEntry->update(['paid_participants' => $paid]);

        return back();
    }
}
