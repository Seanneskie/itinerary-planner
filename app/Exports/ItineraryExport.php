<?php

namespace App\Exports;

use App\Models\Itinerary;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ItineraryExport implements FromView
{
    public function __construct(private Itinerary $itinerary)
    {
    }

    public function view(): View
    {
        return view('itineraries.export', [
            'itinerary' => $this->itinerary,
        ]);
    }
}
