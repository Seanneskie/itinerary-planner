<div x-show="openBookingEditModal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40"
     x-cloak
     @keydown.escape.window="openBookingEditModal = false"
     style="display: none;">
    @include('booking.booking-form', ['itinerary' => $itinerary])
</div>
