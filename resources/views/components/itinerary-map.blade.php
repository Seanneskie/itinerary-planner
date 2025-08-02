@props(['activities', 'bookings' => []])
@php
    $mapId = 'itinerary-map-' . uniqid();
@endphp

<div id="{{ $mapId }}" class="h-80 w-full rounded-lg shadow"></div>

@push('scripts')
<style>
    .map-popup .leaflet-popup-content-wrapper {
        background-color: #ffffff;
        color: #1f2937;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }
    .map-popup .leaflet-popup-tip {
        background-color: #ffffff;
    }
    .dark .map-popup .leaflet-popup-content-wrapper {
        background-color: #1f2937;
        color: #f9fafb;
    }
    .dark .map-popup .leaflet-popup-tip {
        background-color: #1f2937;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const lightUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    const darkUrl = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';
    const isDark = document.documentElement.classList.contains('dark');
    const map = L.map(@js($mapId)).setView([6.11, 125.17], 11);
    let tileLayer = L.tileLayer(isDark ? darkUrl : lightUrl, { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);

    const markers     = {};
    const markerCoords = [];

    @foreach($activities->sortBy('scheduled_at') as $act)
        @if($act->latitude && $act->longitude)
            const m{{ $act->id }} = L.marker([
                {{ $act->latitude }}, {{ $act->longitude }}
            ], { title: "{{ $act->title }}" })
            .bindPopup(`
                <div class="p-2">
                    <h3 class="font-semibold text-gray-800 dark:text-white">{{ $act->title }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $act->location ?? '' }}</p>
                </div>
            `, { className: 'map-popup' })
            .addTo(map);
            markers['marker-{{ $act->id }}'] = m{{ $act->id }};
            markerCoords.push([{{ $act->latitude }}, {{ $act->longitude }}]);
        @endif
    @endforeach

    @foreach($bookings as $booking)
        @if($booking->latitude && $booking->longitude)
            const b{{ $booking->id }} = L.marker([
                {{ $booking->latitude }}, {{ $booking->longitude }}
            ], { title: "{{ $booking->place }}" })
            .bindPopup(`
                <div class="p-2">
                    <h3 class="font-semibold text-gray-800 dark:text-white">{{ $booking->place }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $booking->location ?? '' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Check-in: {{ $booking->check_in }}<br>Check-out: {{ $booking->check_out }}</p>
                </div>
            `, { className: 'map-popup' })
            .addTo(map);
            markers['booking-{{ $booking->id }}'] = b{{ $booking->id }};
            markerCoords.push([{{ $booking->latitude }}, {{ $booking->longitude }}]);
        @endif
    @endforeach

    if (markerCoords.length > 1){
        map.fitBounds(markerCoords, {padding:[20,20]});
    } else if (markerCoords.length === 1){
        map.setView(markerCoords[0], 13);
    }

    window.highlightMarker = id => {
        if(markers[id]) {
            markers[id].setIcon(
                L.icon({iconUrl:'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                        iconSize:[25,41], iconAnchor:[12,41], className:'ring-2 ring-yellow-400'})
            );
            map.panTo(markers[id].getLatLng());
        }
    };
    window.resetMarker = id => {
        if(markers[id]) {
            markers[id].setIcon(new L.Icon.Default());
        }
    };
    window.openPopup = id => {
        if(markers[id]) { markers[id].openPopup(); }
    };

    document.addEventListener('theme-changed', () => {
        const url = document.documentElement.classList.contains('dark') ? darkUrl : lightUrl;
        tileLayer.setUrl(url);
    });
});
</script>
@endpush
