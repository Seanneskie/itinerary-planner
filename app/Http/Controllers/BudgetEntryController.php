<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetEntryRequest;
use App\Http\Requests\UpdateBudgetEntryRequest;
use App\Models\BudgetEntry;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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

        $perPage = 10;

        if ($filter) {
            $entries = BudgetEntry::where('itinerary_id', $itinerary->id)
                ->where('category', $filter)
                ->get();

            $budgetEntries = new LengthAwarePaginator(
                $entries,
                1,
                $perPage,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $page = LengthAwarePaginator::resolveCurrentPage();

            $categoryQuery = BudgetEntry::where('itinerary_id', $itinerary->id)
                ->select('category')
                ->distinct();

            $totalCategories = (clone $categoryQuery)->count();

            $pageCategories = (clone $categoryQuery)
                ->orderBy('category')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->pluck('category')
                ->all();

            $entriesQuery = BudgetEntry::where('itinerary_id', $itinerary->id);

            if (in_array(null, $pageCategories, true)) {
                $entriesQuery->where(function ($q) use ($pageCategories) {
                    $nonNull = array_filter($pageCategories, fn ($c) => !is_null($c));
                    if ($nonNull) {
                        $q->whereIn('category', $nonNull)->orWhereNull('category');
                    } else {
                        $q->whereNull('category');
                    }
                });
            } else {
                $entriesQuery->whereIn('category', $pageCategories);
            }

            $entries = $entriesQuery->get();

            $budgetEntries = new LengthAwarePaginator(
                $entries,
                $totalCategories,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

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
        $this->authorize('view', $budgetEntry);

        $budgetEntry->load('itinerary.groupMembers');

        return view('budgets.show', compact('budgetEntry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BudgetEntry $budgetEntry)
    {
        $this->authorize('update', $budgetEntry);

        $budgetEntry->load('itinerary.groupMembers');

        return view('budgets.edit', compact('budgetEntry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBudgetEntryRequest $request, BudgetEntry $budgetEntry)
    {
        $this->authorize('update', $budgetEntry);

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
        $this->authorize('delete', $budgetEntry);

        $budgetEntry->delete();

        return back()->with('success', 'Entry removed.');
    }

    public function editSpent(BudgetEntry $budgetEntry)
    {
        $this->authorize('update', $budgetEntry);

        return view('budgets.edit-spent', compact('budgetEntry'));
    }

    public function updateSpent(Request $request, BudgetEntry $budgetEntry)
    {
        $this->authorize('update', $budgetEntry);

        $validated = $request->validate([
            'spent_amount' => 'required|numeric|min:0',
        ]);

        $budgetEntry->update($validated);

        return back()->with('success', 'Spent amount updated.');
    }

    public function togglePaid(Request $request, BudgetEntry $budgetEntry, int $member)
    {
        $this->authorize('update', $budgetEntry);

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
