<div x-show="openEditModal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40"
     x-cloak
     @keydown.escape.window="openEditModal = false"
     style="display: none;">
    @include('activity.activity-form', ['itinerary' => $itinerary])

</div>
