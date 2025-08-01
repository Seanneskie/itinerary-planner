{{-- Itinerary Card --}}
@props(['itinerary'])

{{-- One Alpine scope controls everything inside --}}
<div x-data="{
        openActivityForm: false,
        openEditModal : false,
        activity      : {}        // currently-edited activity
    }" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 space-y-4">
    <!-- ── Title & Info ─────────────────────────────────────────────── -->
    <div>
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
            {{ $itinerary->title }}
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-300">
            {{ $itinerary->description }}
        </p>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $itinerary->start_date }} to {{ $itinerary->end_date }}
        </p>
    </div>

    <!-- ── Activities List ──────────────────────────────────────────── -->
    @if($itinerary->activities->count())
        <x-activity-list :activities="$itinerary->activities" />
    @else
        <p class="text-sm text-gray-400 italic">No activities yet.</p>
    @endif

    <!-- ── Add-Activity Button ──────────────────────────────────────── -->
    <div>
        <button @click="
        activity = {};           /* blank object for new record */
        openActivityForm = true
    " class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded">
            + Add Activity
        </button>
    </div>

    <!-- ── Add-Activity Modal ───────────────────────────────────────── -->
    <div x-show="openActivityForm" x-transition.opacity.scale.80 x-cloak
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
        @include('activity.activity-form', ['itinerary' => $itinerary])
    </div>

    <!-- ── Edit-Activity Modal (shared scope) ───────────────────────── -->
    <div x-show="openEditModal" x-transition.opacity.scale.80 x-cloak
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
        @include('activity.edit-modal')
    </div>

    <!-- ── Map (hidden while any modal is open) ─────────────────────── -->
    <div x-show="!openActivityForm && !openEditModal" x-transition.opacity>
        <x-itinerary-map :activities="$itinerary->activities" />
    </div>

    <!-- ── Leaflet “drop-pin” helper (only used by add-form) ────────── -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.effect(() => {
                Alpine.nextTick(() => {
                    const mapContainer = document.getElementById('drop-pin-map');
                    if (mapContainer && !mapContainer.dataset.mapInitialized) {
                        mapContainer.dataset.mapInitialized = true;

                        const map = L.map('drop-pin-map').setView([6.11, 125.17], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);

                        let marker;
                        map.on('click', e => {
                            const { lat, lng } = e.latlng;
                            document.getElementById('latitude').value = lat.toFixed(7);
                            document.getElementById('longitude').value = lng.toFixed(7);

                            if (marker) marker.setLatLng(e.latlng);
                            else marker = L.marker(e.latlng).addTo(map);
                        });
                    }
                });
            });
        });
    </script>
</div>