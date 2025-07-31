<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Itinerary;

class DashboardController extends Controller
{
    public function index()
    {
        $itineraries = Itinerary::with('activities')
            ->where('user_id', auth()->id())
            ->get();

        return view('dashboard', compact('itineraries'));
    }
}
