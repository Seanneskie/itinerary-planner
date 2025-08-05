@props(['bookings'])
@php $itemCount = count($bookings); @endphp
<div x-data="{ limit: 5, count: {{ $itemCount }} }">
<ul id="booking-list" class="mt-4 space-y-3 divide-y divide-gray-200 dark:divide-gray-700">
    @foreach($bookings as $index => $booking)
        <li x-data="{ openDelete: false }" x-show="{{ $index }} < limit" class="pt-3 first:pt-0 flex justify-between items-start gap-3 hover:bg-gray-50 dark:hover:bg-gray-700/40 rounded-md px-3 py-2 transition" data-marker-id="booking-{{ $booking->id }}">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $booking->place }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $booking->check_in }} - {{ $booking->check_out }}
                    @if($booking->location)
                        • {{ $booking->location }}
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-1">
                <button type="button" @click.prevent.stop="booking = {{ $booking->toJson() }}; openBookingEditModal = true; $dispatch('open-modal', { detail: 'edit-booking-{{ $booking->itinerary_id }}' })" class="inline-flex items-center px-2 py-1 bg-primary hover:bg-primary-dark text-white rounded text-xs font-medium">Edit</button>
                <button @click="openDelete = true" class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium">Delete</button>
                <div x-show="openDelete" x-cloak x-transition.opacity.scale.80 class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-sm">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-2">Confirm Delete</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                            Are you sure you want to delete <span class="font-semibold">{{ $booking->place }}</span>?
                            This action cannot be undone.
                        </p>
                        <div class="flex justify-end gap-3">
                            <button @click="openDelete = false" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded">Cancel</button>
                            <form method="POST" action="{{ route('bookings.destroy', $booking->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    @endforeach
</ul>
<button
    x-show="count > 5"
    @click="limit = limit === 5 ? count : 5"
    @keydown.enter.prevent="limit = limit === 5 ? count : 5"
    @keydown.space.prevent="limit = limit === 5 ? count : 5"
    :aria-expanded="limit > 5"
    aria-controls="booking-list"
    :aria-label="limit === 5 ? 'Show more bookings' : 'Show less bookings'"
    class="mt-2 text-sm text-primary hover:underline">
    <span x-text="limit === 5 ? 'Show more' : 'Show less'"></span>
</button>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#booking-list [data-marker-id]').forEach(row => {
        const id = row.dataset.markerId;
        row.addEventListener('mouseover', () => window.highlightMarker?.(id));
        row.addEventListener('mouseout', () => window.resetMarker?.(id));
        row.addEventListener('click', () => window.openPopup?.(id));
    });
});
</script>
@endpush
