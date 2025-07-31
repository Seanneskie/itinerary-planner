@props(['activities'])

<div id="itinerary-map" class="h-80 w-full rounded-lg shadow"></div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const map = L.map('itinerary-map').setView([6.11, 125.17], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);

    const markers   = {};
    const pathCoords = [];

    @foreach($activities->sortBy('scheduled_at') as $act)
        @if($act->latitude && $act->longitude)
            const m{{ $act->id }} = L.marker(
                [{{ $act->latitude }}, {{ $act->longitude }}],
                { title: "{{ $act->title }}" }
            )
            .bindPopup(`<strong>{{ $act->title }}</strong><br>{{ $act->location ?? '' }}`)
            .addTo(map);

            markers['marker-{{ $act->id }}'] = m{{ $act->id }};
            pathCoords.push([{{ $act->latitude }}, {{ $act->longitude }}]);
        @endif
    @endforeach

    if (pathCoords.length > 1){
        const line = L.polyline(pathCoords, {color:'blue'}).addTo(map);
        map.fitBounds(line.getBounds(), {padding:[20,20]});
    } else if (pathCoords.length === 1){
        map.setView(pathCoords[0], 13);
    }

    // expose helpers for list component
    window.highlightMarker = id => {
        if(markers[id]) {
            markers[id].setIcon(
                L.icon({iconUrl:'https://unpkg.com/leaflet@1.9.3/dist/images/marker-icon-2x.png',
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
});
</script>
@endpush
