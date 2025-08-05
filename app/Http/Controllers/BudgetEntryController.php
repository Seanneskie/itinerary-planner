<?php

namespace App\Http\Controllers;

use App\Models\BudgetEntry;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
    public function store(Request $request)
    {
        $itinerary = Itinerary::where('user_id', Auth::id())
            ->with('groupMembers')
            ->findOrFail($request->itinerary_id);

        $validated = $request->validate([
            'itinerary_id' => 'required|exists:itineraries,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'entry_date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'participants' => 'array|nullable',
            'participants.*' => [
                'integer',
                Rule::exists('group_members', 'id')->where('itinerary_id', $itinerary->id),
            ],
            'paid_participants' => 'array|nullable',
            'paid_participants.*' => [
                'integer',
                Rule::exists('group_members', 'id')->where('itinerary_id', $itinerary->id),
            ],
        ]);

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
    public function update(Request $request, BudgetEntry $budgetEntry)
    {
        if (! $budgetEntry->itinerary || (int) $budgetEntry->itinerary->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'entry_date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'participants' => 'array|nullable',
            'participants.*' => [
                'integer',
                Rule::exists('group_members', 'id')->where('itinerary_id', $budgetEntry->itinerary->id),
            ],
            'paid_participants' => 'array|nullable',
            'paid_participants.*' => [
                'integer',
                Rule::exists('group_members', 'id')->where('itinerary_id', $budgetEntry->itinerary->id),
            ],
        ]);

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
