<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreItineraryRequest;
use App\Http\Requests\UpdateItineraryRequest;
use App\Models\Itinerary;
use App\Models\BudgetEntry;
use Illuminate\Support\Facades\Auth;
use App\Exports\ItineraryExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Itinerary::with(['activities', 'groupMembers', 'bookings'])
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $itineraries = $query->orderBy('start_date', 'desc')
            ->paginate(5)
            ->withQueryString();

        return view('dashboard', compact('itineraries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('itineraries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
 
    public function store(StoreItineraryRequest $request)
    {
        $validated = $request->validated();

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('itineraries', 'public');
        }

        Itinerary::create($validated);

        return redirect()->route('dashboard')->with('success', 'Itinerary created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $itinerary = Itinerary::with(['groupMembers', 'budgetEntries', 'bookings'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $activities = $itinerary->activities()
            ->orderBy('scheduled_at')
            ->paginate(10);

        $primaryLocation = $activities->first()->location ?? null;
        $averageBudget = null;

        if ($primaryLocation) {
            $averageBudget = BudgetEntry::whereIn('itinerary_id', function ($query) use ($primaryLocation, $itinerary) {
                $query->select('itineraries.id')
                    ->from('itineraries')
                    ->join('activities', 'itineraries.id', '=', 'activities.itinerary_id')
                    ->where('activities.location', $primaryLocation)
                    ->where('itineraries.id', '!=', $itinerary->id);
            })->avg('amount');
        }

        return view('itineraries.show', compact('itinerary', 'activities', 'averageBudget', 'primaryLocation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $itinerary = Itinerary::where('user_id', Auth::id())->findOrFail($id);
        return view('itineraries.edit', compact('itinerary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItineraryRequest $request, string $id)
    {
        $itinerary = Itinerary::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('itineraries', 'public');
        }

        $itinerary->update($validated);

        return redirect()->route('dashboard')->with('success', 'Itinerary updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $itinerary = Itinerary::where('user_id', Auth::id())->findOrFail($id);
        $itinerary->delete();

        return redirect()->route('dashboard')->with('success', 'Itinerary deleted.');
    }

    public function exportExcel(Itinerary $itinerary)
    {
        $this->authorize('view', $itinerary);

        $itinerary->load(['activities', 'groupMembers', 'bookings', 'budgetEntries']);

        return Excel::download(new ItineraryExport($itinerary), 'itinerary.xlsx');
    }

    public function exportPdf(Itinerary $itinerary)
    {
        $this->authorize('view', $itinerary);

        $itinerary->load(['activities', 'groupMembers', 'bookings', 'budgetEntries']);

        $pdf = Pdf::loadView('itineraries.export', [
            'itinerary' => $itinerary,
        ]);

        return $pdf->download('itinerary.pdf');
    }
}
