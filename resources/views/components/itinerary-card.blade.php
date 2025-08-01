{{-- Itinerary Card --}}
@props(['itinerary'])

{{-- One Alpine scope controls everything inside --}}
<div x-data="{
        openActivityForm: false,
        openEditModal : false,
        openDeleteModal: false,
        openMemberModal: false,
        activity      : {}        // currently-edited activity
    }" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 space-y-4">
    <!-- ── Title & Info ─────────────────────────────────────────────── -->
    <div>
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
            {{ $itinerary->title }}
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-300">
            {{ $itinerary->description }}
        </p>
    <p class="text-sm text-gray-500 dark:text-gray-400">
        {{ $itinerary->start_date }} to {{ $itinerary->end_date }}
    </p>

    @if($itinerary->groupMembers->count())
        <x-group-member-list :members="$itinerary->groupMembers" />
    @else
        <p class="text-sm text-gray-400 italic">No group members yet.</p>
    @endif

    <button @click="openMemberModal = true"
        class="mt-2 px-3 py-1 bg-primary hover:bg-primary-light text-white rounded text-sm">
        + Add Member
    </button>

    <div x-show="openMemberModal" x-cloak x-transition.opacity.scale.80
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Add Member</h2>
            @include('group-member.form', ['itinerary' => $itinerary])
            <div class="text-right mt-2">
                <button @click="openMemberModal = false"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded">
                    Close
                </button>
            </div>
        </div>
    </div>

        <div class="mt-2 flex items-center gap-2 text-sm">
            <a href="{{ route('itineraries.edit', $itinerary->id) }}" class="text-primary hover:underline">Edit</a>

            <button @click="openDeleteModal = true" class="text-red-600 hover:underline">Delete</button>
            <div x-show="openDeleteModal" x-cloak x-transition.opacity.scale.80
                class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-sm">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-2">Confirm Delete</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        Are you sure you want to delete <span class="font-semibold">{{ $itinerary->title }}</span>
                        scheduled from {{ $itinerary->start_date }} to {{ $itinerary->end_date }}?
                        This action cannot be undone.
                    </p>
                    <div class="flex justify-end gap-3">
                        <button @click="openDeleteModal = false"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded">Cancel</button>
                        <form method="POST" action="{{ route('itineraries.destroy', $itinerary->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Activities List ──────────────────────────────────────────── -->
    @if($itinerary->activities->count())
        <x-activity-list :activities="$itinerary->activities" />
    @else
        <p class="text-sm text-gray-400 italic">No activities yet.</p>
    @endif

    <!-- ── Add-Activity Button ──────────────────────────────────────── -->
    <div class="flex gap-2">
        <x-primary-button type="button" class="text-xs" @click="activity = {}; openActivityForm = true">
            + Add Activity
        </x-primary-button>
        <a href="{{ route('itineraries.budgets.index', $itinerary->id) }}"
            class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-md">
            Budget
        </a>
        <a href="{{ route('itineraries.show', $itinerary->id) }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-xs rounded-md">
            Details
        </a>
    </div>

    <!-- ── Add-Activity Modal ───────────────────────────────────────── -->
    <div x-show="openActivityForm" x-transition.opacity.scale.80 x-cloak
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
        @include('activity.activity-form', ['itinerary' => $itinerary])
    </div>

    <!-- ── Edit-Activity Modal (shared scope) ───────────────────────── -->
    <div x-show="openEditModal" x-transition.opacity.scale.80 x-cloak
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
        @include('activity.edit-modal')
    </div>

    <!-- ── Map (hidden while any modal is open) ─────────────────────── -->
    <div x-show="!openActivityForm && !openEditModal" x-transition.opacity>
        <x-itinerary-map :activities="$itinerary->activities" />
    </div>

</div>

