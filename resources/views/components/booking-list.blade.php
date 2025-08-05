@props(['bookings'])
<ul id="booking-list" class="mt-4 space-y-3 divide-y divide-gray-200 dark:divide-gray-700">
    @foreach($bookings as $booking)
        <li class="pt-3 first:pt-0 flex justify-between items-start gap-3 hover:bg-gray-50 dark:hover:bg-gray-700/40 rounded-md px-3 py-2 transition" data-marker-id="booking-{{ $booking->id }}">
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
                <button @click="$dispatch('open-modal', 'delete-booking-{{ $booking->id }}')" class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium">Delete</button>
                <x-confirm-dialog name="delete-booking-{{ $booking->id }}" title="Confirm Delete">
                    <x-slot name="message">
                        Are you sure you want to delete <span class="font-semibold">{{ $booking->place }}</span>? This action cannot be undone.
                    </x-slot>
                    <x-slot name="confirm">
                        <form method="POST" action="{{ route('bookings.destroy', $booking->id) }}">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>Delete</x-danger-button>
                        </form>
                    </x-slot>
                </x-confirm-dialog>
            </div>
        </li>
    @endforeach
</ul>

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
