@props(['activities'])

@php
    $start = ($activities instanceof \Illuminate\Contracts\Pagination\Paginator)
        ? $activities->firstItem()
        : 1;
@endphp

<ul id="activity-list"
    class="mt-4 space-y-3 divide-y divide-gray-200 dark:divide-gray-700">
@foreach ($activities as $index => $activity)
    <li
        class="pt-3 first:pt-0 grid grid-cols-[auto_auto_1fr_auto] gap-3
               hover:bg-gray-50 dark:hover:bg-gray-700/40 rounded-md px-3 py-2 transition"
        data-marker-id="marker-{{ $activity->id }}"
    >
        {{-- Sequence bubble --}}
        <span
            class="self-center flex h-6 w-6 items-center justify-center rounded-full
                   text-xs font-bold bg-primary text-white dark:bg-primary-light">
            {{ $start + $index }}
        </span>

        {{-- Photo --}}
        <div class="self-center">
            @if ($activity->photo_path)
                <img src="{{ asset('storage/' . $activity->photo_path) }}" alt="{{ $activity->title }} photo" class="w-12 h-12 object-cover rounded-md">
            @else
                <div class="w-12 h-12 flex items-center justify-center rounded-md bg-gray-200 dark:bg-gray-700 text-gray-500 text-xs">N/A</div>
            @endif
        </div>

        {{-- Activity meta --}}
        <div class="min-w-0">
            <p class="text-sm font-medium text-gray-800 dark:text-white truncate">
                {{ $activity->title }}
            </p>

            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ \Carbon\Carbon::parse($activity->scheduled_at)->format('M j, g:i A') }}
                @if ($activity->location) • {{ $activity->location }} @endif
            </p>

            @if ($activity->note)
                <div x-data="{ expanded: false }">
                    <p class="text-xs italic text-gray-500 dark:text-gray-400" :class="expanded ? '' : 'line-clamp-2'">
                        {{ $activity->note }}
                    </p>
                    <button type="button"
                            class="mt-1 text-[11px] text-primary hover:underline"
                            @click="expanded = !expanded">
                        <span x-show="!expanded">Show more</span>
        
                        <span x-show="expanded">Show less</span>
                    </button>
                </div>
            @endif
            @if ($activity->budget)
                <p class="text-xs text-gray-500 dark:text-gray-400">Budget: PHP{{ number_format($activity->budget, 2) }}</p>
            @endif
            @if ($activity->attire_color || $activity->attire_note)
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    @if($activity->attire_color)
                        Color: {{ $activity->attire_color }}
                    @endif
                    @if($activity->attire_note)
                        • {{ $activity->attire_note }}
                    @endif
                </p>
            @endif
        </div>

        {{-- Duration & actions --}}
        <div class="flex flex-col items-end justify-between gap-1 text-right">
            @php
                $diff = now()->diffInHours($activity->scheduled_at, false);
            @endphp
            <span class="text-[11px] font-semibold {{ $diff < 0 ? 'text-red-500' : 'text-green-600' }}">
                {{ $diff < 0 ? 'Past' : $diff . ' h' }}
            </span>

            <div class="flex items-center gap-1">
                {{-- Edit (opens modal in PARENT scope) --}}
                <button type="button"
                   @click.prevent.stop="
                       activity      = {{ $activity->toJson() }};
                       openEditModal = true;
                       $dispatch('open-modal', { detail: 'edit-activity-{{ $activity->itinerary_id }}' })
                   "
                   class="inline-flex items-center px-2 py-1 bg-primary hover:bg-primary-dark text-white rounded text-xs font-medium">
                    Edit
                </button>

                {{-- Delete --}}
                <button @click="$dispatch('open-modal', 'delete-activity-{{ $activity->id }}')"
                        class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium">
                    Delete
                </button>
                <x-confirm-dialog name="delete-activity-{{ $activity->id }}" title="Confirm Delete">
                    <x-slot name="message">
                        Are you sure you want to delete <span class="font-semibold">{{ $activity->title }}</span>
                        scheduled for {{ \Carbon\Carbon::parse($activity->scheduled_at)->format('M j, g:i A') }}@if($activity->location)
                            at {{ $activity->location }}@endif? This action cannot be undone.
                    </x-slot>
                    <x-slot name="confirm">
                        <form method="POST" action="{{ route('activities.destroy', $activity->id) }}">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>Delete</x-danger-button>
                        </form>
                    </x-slot>
                </x-confirm-dialog>
            </div>
        </div>
    </li>
@endforeach
</ul>

@if ($activities instanceof \Illuminate\Contracts\Pagination\Paginator)
    <div class="mt-4">
        {{ $activities->links() }}
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Hover/click → map interaction
    document.querySelectorAll('#activity-list [data-marker-id]').forEach(row => {
        const id = row.dataset.markerId;
        row.addEventListener('mouseover', ()  => window.highlightMarker?.(id));
        row.addEventListener('mouseout',  ()  => window.resetMarker?.(id));
        row.addEventListener('click',     ()  => window.openPopup?.(id));
    });
});
</script>
@endpush
