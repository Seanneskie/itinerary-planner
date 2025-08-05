@props(['itinerary', 'closeEvent'])
<div
    x-init="() => {}"
    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg ring-1 ring-gray-200 dark:ring-gray-700 w-full max-w-lg p-6 space-y-4 text-sm"
>
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white" x-text="booking.id ? 'Edit Booking' : 'New Booking'">New Booking</h3>
    <form
        :action="booking.id ? ('/bookings/' + booking.id) : '{{ route('itineraries.bookings.store', $itinerary->id) }}'"
        method="POST"
        class="space-y-6"
    >
        @csrf
        <template x-if="booking.id">
            <input type="hidden" name="_method" value="PUT">
        </template>
        <input type="hidden" name="itinerary_id" :value="booking.itinerary_id ?? '{{ $itinerary->id }}'">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Place <span class="text-red-500">*</span></label>
                <input type="text" name="place" x-model="booking.place" required class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 focus:ring-primary focus:ring-2 outline-none">
            </div>
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Location</label>
                <input type="text" name="location" x-model="booking.location" class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 focus:ring-primary focus:ring-2 outline-none">
            </div>
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Check-in <span class="text-red-500">*</span></label>
                <input type="date" name="check_in" x-model="booking.check_in" required class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 focus:ring-primary focus:ring-2 outline-none">
            </div>
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Check-out <span class="text-red-500">*</span></label>
                <input type="date" name="check_out" x-model="booking.check_out" required class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 focus:ring-primary focus:ring-2 outline-none">
            </div>
            <div class="sm:col-span-2 space-y-2">
                <label class="font-medium text-gray-700 dark:text-gray-300">Drop a pin</label>
                <div id="booking-drop-pin-map" x-ref="pinMap" x-init="() => {
                        if ($el.dataset.loaded) return;
                        const defaultLat = 6.11;
                        const defaultLng = 125.17;
                        const lat = parseFloat(($refs.lat.value || defaultLat));
                        const lng = parseFloat(($refs.lng.value || defaultLng));
                        if (!$refs.lat.value) $refs.lat.value = lat.toFixed(7);
                        if (!$refs.lng.value) $refs.lng.value = lng.toFixed(7);
                        const map = L.map($el).setView([lat, lng], 13);
                        setTimeout(() => map.invalidateSize(), 0);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap contributors' }).addTo(map);
                        let marker;
                        const updateInputs = ({ lat, lng }) => { $refs.lat.value = lat.toFixed(7); $refs.lng.value = lng.toFixed(7); };
                        if ($refs.lat.value && $refs.lng.value) {
                            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                            marker.on('dragend', e => updateInputs(e.target.getLatLng()));
                        }
                        map.on('click', ({ latlng }) => {
                            const { lat: lt, lng: lg } = latlng;
                            updateInputs({ lat: lt, lng: lg });
                            if (marker) {
                                marker.setLatLng(latlng);
                            } else {
                                marker = L.marker(latlng, { draggable: true }).addTo(map);
                                marker.on('dragend', e => updateInputs(e.target.getLatLng()));
                            }
                        });
                        $watch('booking.id', () => {
                            if (!booking.id) return;
                            const lt = parseFloat(booking.latitude || 6.11);
                            const lg = parseFloat(booking.longitude || 125.17);
                            map.setView([lt, lg], 13);
                            if (marker) {
                                marker.setLatLng([lt, lg]);
                            } else {
                                marker = L.marker([lt, lg], { draggable: true }).addTo(map);
                                marker.on('dragend', e => updateInputs(e.target.getLatLng()));
                            }
                            updateInputs({ lat: lt, lng: lg });
                        });
                        $watch('openBookingForm', value => { if (value) setTimeout(() => map.invalidateSize(), 0); });
                        $watch('openBookingEditModal', value => { if (value) setTimeout(() => map.invalidateSize(), 0); });
                        $el.dataset.loaded = true;
                    }" class="h-48 w-full rounded-md overflow-hidden ring-1 ring-gray-300 dark:ring-gray-600"></div>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    <input type="text" x-ref="lat" id="latitude" name="latitude" readonly placeholder="Lat" :value="booking.latitude" class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-inset ring-gray-300">
                    <input type="text" x-ref="lng" id="longitude" name="longitude" readonly placeholder="Lng" :value="booking.longitude" class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-inset ring-gray-300">
                </div>
            </div>
        </div>
        <div class="flex justify-end space-x-3">
            <button type="button" @click="$dispatch('close-modal', { detail: '{{ $closeEvent }}' }); openBookingForm = false; openBookingEditModal = false" class="px-4 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100">Cancel</button>
            <button type="submit" class="px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white shadow">
                <span x-text="booking.id ? 'Update' : 'Save'"></span>
            </button>
        </div>
    </form>
</div>

