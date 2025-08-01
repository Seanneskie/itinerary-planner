<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Itinerary;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Itinerary::with('activities')
            ->where('user_id', auth()->id());

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
}
