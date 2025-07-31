<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ItineraryCard extends Component
{
    public $itinerary;

    public function __construct($itinerary)
    {
        $this->itinerary = $itinerary;
    }

    public function render(): View|Closure|string
    {
        return view('components.itinerary-card');
    }
}
