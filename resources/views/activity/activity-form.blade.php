@props(['itinerary'])

<div
    x-init="() => {
        /* ---- normalise scheduled_at for datetime-local ---- */
        if (activity.id && activity.scheduled_at && !activity.scheduled_at.includes('T')) {
            activity.scheduled_at = new Date(activity.scheduled_at).toISOString().slice(0, 16);
        }
    }"
    @click.away="openActivityForm = false; openEditModal = false"
    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg ring-1 ring-gray-200 dark:ring-gray-700
           w-full max-w-lg p-6 space-y-4 text-sm"
>
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white"
        x-text="activity.id ? 'Edit Activity' : 'New Activity'">
        New Activity
    </h3>

    <form
        :action="activity.id
            ? ('/activities/' + activity.id)          /* PUT  → /activities/{id} */
            : '{{ route('activities.store') }}'        /* POST → /activities      */"
        method="POST"
        class="space-y-6"
    >
        @csrf

        <!-- hidden _method when editing -->
        <template x-if="activity.id">
            <input type="hidden" name="_method" value="PUT">
        </template>

        <!-- always pass itinerary -->
        <input type="hidden"
               name="itinerary_id"
               :value="activity.itinerary_id ?? '{{ $itinerary->id }}'">

        <!-- ── form grid ─────────────────────────────────────── -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <!-- Title -->
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" required
                       x-model="activity.title"
                       class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white
                              ring-1 ring-inset ring-gray-300 focus:ring-primary focus:ring-2 outline-none">
            </div>

            <!-- Location -->
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Location</label>
                <input type="text" name="location"
                       x-model="activity.location"
                       class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white
                              ring-1 ring-inset ring-gray-300 focus:ring-primary focus:ring-2 outline-none">
            </div>

            <!-- Map selector -->
            <div class="sm:col-span-2 space-y-2">
                <label class="font-medium text-gray-700 dark:text-gray-300">Drop a pin</label>

                <div id="drop-pin-map"
                     x-ref="pinMap"
                     x-init="() => {
                        /* prevent double-init within same modal instance */
                        if ($el.dataset.loaded) return;

                        /* centre map on existing coords or default */
                        const defaultLat = 6.11;
                        const defaultLng = 125.17;
                        const lat = parseFloat(($refs.lat.value || defaultLat));
                        const lng = parseFloat(($refs.lng.value || defaultLng));

                        // populate empty inputs with default coordinates
                        if (!$refs.lat.value) $refs.lat.value = lat.toFixed(7);
                        if (!$refs.lng.value) $refs.lng.value = lng.toFixed(7);

                        const map = L.map($el).setView([lat, lng], 13);
                        // ensure proper sizing when modal becomes visible
                        setTimeout(() => map.invalidateSize(), 0);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);

                        let marker;
                        const updateInputs = ({ lat, lng }) => {
                            $refs.lat.value = lat.toFixed(7);
                            $refs.lng.value = lng.toFixed(7);
                        };

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

                        /* watch: recenter when a different activity is loaded */
                        $watch('activity.id', () => {
                            if (!activity.id) return;          // create mode
                            const lt = parseFloat(activity.latitude  || 6.11);
                            const lg = parseFloat(activity.longitude || 125.17);
                            map.setView([lt, lg], 13);
                            if (marker) {
                                marker.setLatLng([lt, lg]);
                            } else {
                                marker = L.marker([lt, lg], { draggable: true }).addTo(map);
                                marker.on('dragend', e => updateInputs(e.target.getLatLng()));
                            }
                            updateInputs({ lat: lt, lng: lg });
                        });

                        $watch('openActivityForm', value => { if (value) setTimeout(() => map.invalidateSize(), 0); });
                        $watch('openEditModal',   value => { if (value) setTimeout(() => map.invalidateSize(), 0); });

                        $el.dataset.loaded = true;
                     }"
                     class="h-48 w-full rounded-md overflow-hidden ring-1 ring-gray-300 dark:ring-gray-600">
                </div>

                <div class="grid grid-cols-2 gap-2 mt-2">
                    <input type="text"  x-ref="lat" id="latitude"  name="latitude"  readonly placeholder="Lat"
                           :value="activity.latitude"
                           class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-inset ring-gray-300">
                    <input type="text"  x-ref="lng" id="longitude" name="longitude" readonly placeholder="Lng"
                           :value="activity.longitude"
                           class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-inset ring-gray-300">
                </div>
            </div>

            <!-- Date & time -->
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Date &amp; time</label>
                <input type="datetime-local" name="scheduled_at" required
                       x-model="activity.scheduled_at"
                       class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white
                              ring-1 ring-inset ring-gray-300 focus:ring-primary focus:ring-2 outline-none">
            </div>

            <!-- Notes -->
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea name="note" rows="1"
                          x-model="activity.note"
                          class="px-3 py-2 rounded-md resize-y bg-gray-50 dark:bg-gray-900 dark:text-white
                                 ring-1 ring-inset ring-gray-300 focus:ring-primary focus:ring-2 outline-none"></textarea>
            </div>
        </div>

        <!-- ── actions ────────────────────────────────────────── -->
        <div class="flex justify-end space-x-3">
            <button type="button"
                    @click="openActivityForm = false; openEditModal = false"
                    class="px-4 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                Cancel
            </button>

            <button type="submit"
                    class="px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white shadow">
                <span x-text="activity.id ? 'Update' : 'Save'"></span>
            </button>
        </div>
    </form>
</div>
