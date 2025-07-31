@props(['activities'])

@php
    // Pre-sort so list order == path order
    $sorted = $activities->sortBy('scheduled_at');
@endphp

<ul id="activity-list"
    class="mt-4 space-y-3 divide-y divide-gray-200 dark:divide-gray-700">
@foreach($sorted as $index => $activity)
    <li  class="pt-3 first:pt-0 grid grid-cols-[auto_1fr_auto] gap-3
                hover:bg-gray-50 dark:hover:bg-gray-700/40 rounded-md px-3 py-2
                transition"
         data-marker-id="marker-{{ $activity->id }}">

        {{-- Sequence bubble --}}
        <span class="self-center flex h-6 w-6 items-center justify-center rounded-full
                     text-xs font-bold
                     bg-blue-600 text-white dark:bg-blue-400">
            {{ $index+1 }}
        </span>

        {{-- Activity meta --}}
        <div class="min-w-0">
            <p class="text-sm font-medium text-gray-800 dark:text-white truncate">
                {{ $activity->title }}
            </p>

            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ \Carbon\Carbon::parse($activity->scheduled_at)->format('M j, g:i A') }}
                @if($activity->location)
                    • {{ $activity->location }}
                @endif
            </p>

            @if($activity->note)
                <p class="text-xs italic text-gray-500 dark:text-gray-400 line-clamp-2">
                    {{ $activity->note }}
                </p>
            @endif
        </div>

        {{-- Duration until event (fun extra detail) --}}
        @php
            $diff = now()->diffInHours($activity->scheduled_at, false);
        @endphp
        <span class="self-start text-[11px] font-semibold
                     {{ $diff<0 ? 'text-red-500' : 'text-green-600' }}">
            {{ $diff<0 ? 'Past' : $diff.' h' }}
        </span>
    </li>
@endforeach
</ul>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Bind list-row hover/click → map interaction
    const rows = document.querySelectorAll('#activity-list [data-marker-id]');
    rows.forEach(row => {
        const id = row.dataset.markerId;
        row.addEventListener('mouseover', () => {
            window.highlightMarker(id);
        });
        row.addEventListener('mouseout', () => {
            window.resetMarker(id);
        });
        row.addEventListener('click', () => {
            window.openPopup(id);
        });
    });
});
</script>
@endpush
