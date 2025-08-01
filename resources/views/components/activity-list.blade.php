@props(['activities'])

@php
    // Keep items in path order
    $sorted = $activities->sortBy('scheduled_at');
@endphp

<ul id="activity-list"
    class="mt-4 space-y-3 divide-y divide-gray-200 dark:divide-gray-700">
@foreach ($sorted as $index => $activity)
    <li
        x-data="{ openDelete: false }"
        class="pt-3 first:pt-0 grid grid-cols-[auto_1fr_auto] gap-3
               hover:bg-gray-50 dark:hover:bg-gray-700/40 rounded-md px-3 py-2 transition"
        data-marker-id="marker-{{ $activity->id }}"
    >
        {{-- Sequence bubble --}}
        <span
            class="self-center flex h-6 w-6 items-center justify-center rounded-full
                   text-xs font-bold bg-primary text-white dark:bg-primary-light">
            {{ $index + 1 }}
        </span>

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
                <p class="text-xs italic text-gray-500 dark:text-gray-400 line-clamp-2">
                    {{ $activity->note }}
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
                <a href="#"
                   @click.prevent.stop="
                       activity      = {{ $activity->toJson() }};
                       openEditModal = true;
                   "
                   class="text-primary hover:text-primary-dark dark:text-primary-light dark:hover:text-primary-light text-xs font-medium">
                    Edit
                </a>

                {{-- Delete --}}
                <button @click="openDelete = true"
                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 text-xs font-medium">
                    Delete
                </button>
                <div x-show="openDelete" x-cloak x-transition.opacity.scale.80
                     class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-sm">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Delete this activity?</h2>
                        <div class="flex justify-end gap-3">
                            <button @click="openDelete = false"
                                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded">Cancel</button>
                            <form method="POST" action="{{ route('activities.destroy', $activity->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>
@endforeach
</ul>

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
