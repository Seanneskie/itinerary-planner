@props(['activities'])
@php
    $mapId = 'itinerary-map-' . uniqid();
@endphp

<div id="{{ $mapId }}" class="h-80 w-full rounded-lg shadow"></div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const lightUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    const darkUrl = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';
    const isDark = document.documentElement.classList.contains('dark');
    const map = L.map(@js($mapId)).setView([6.11, 125.17], 11);
    let tileLayer = L.tileLayer(isDark ? darkUrl : lightUrl, { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);

    const markers   = {};
    const pathCoords = [];

    @foreach($activities->sortBy('scheduled_at') as $act)
        @if($act->latitude && $act->longitude)
            const m{{ $act->id }} = L.marker([
                {{ $act->latitude }}, {{ $act->longitude }}
            ], { title: "{{ $act->title }}" })
            .bindPopup(`<strong>{{ $act->title }}</strong><br>{{ $act->location ?? '' }}`)
            .addTo(map);
            markers['marker-{{ $act->id }}'] = m{{ $act->id }};
            pathCoords.push([{{ $act->latitude }}, {{ $act->longitude }}]);
        @endif
    @endforeach

    if (pathCoords.length > 1){
        const line = L.polyline(pathCoords, {color:'#2563eb'}).addTo(map);
        map.fitBounds(line.getBounds(), {padding:[20,20]});
    } else if (pathCoords.length === 1){
        map.setView(pathCoords[0], 13);
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
