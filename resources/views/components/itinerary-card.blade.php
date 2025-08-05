{{-- Itinerary Card --}}
@props(['itinerary', 'activities' => null, 'showActions' => true])

{{-- One Alpine scope controls everything inside --}}
<div x-data="{
        openActivityForm: false,
        openEditModal   : false,
        openBookingForm : false,
        openBookingEditModal: false,
        activity        : {},
        booking         : {}
    }" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 space-y-4">
    @php
        $activities = $activities ?? $itinerary->activities;
        $mapActivities = $activities instanceof \Illuminate\Contracts\Pagination\Paginator
            ? collect($activities->items())
            : $activities;

        $photo = $itinerary->photo_path
            ? Storage::url($itinerary->photo_path)
            : asset('images/default-photo.svg');
    @endphp
    <!-- ── Title, Photo & Info ───────────────────────────────────────── -->
    <div class="flex justify-between items-start">
        <div class="flex gap-4">
            <img src="{{ $photo }}" alt="{{ $itinerary->title }}" class="w-24 h-24 object-cover rounded-md border-2 border-gray-300 dark:border-gray-600">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                    {{ $itinerary->title }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $itinerary->description }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $itinerary->start_date->format('M j, Y') }} to {{ $itinerary->end_date->format('M j, Y') }}
                </p>
            </div>
        </div>
        @if($showActions)
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('itineraries.edit', $itinerary->id) }}"
                   class="inline-flex items-center px-2 py-1 bg-primary hover:bg-primary-dark text-white rounded text-xs">Edit</a>

                <button @click="$dispatch('open-modal', { detail: 'delete-itinerary-{{ $itinerary->id }}' })"
                    class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">Delete</button>

                <a href="{{ route('itineraries.show', $itinerary->id) }}"
                   class="inline-flex items-center px-2 py-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded text-xs">Details</a>

                <x-modal name="delete-itinerary-{{ $itinerary->id }}">
                    <x-slot name="content">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-2">Confirm Delete</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                            Are you sure you want to delete <span class="font-semibold">{{ $itinerary->title }}</span>
                            scheduled from {{ $itinerary->start_date->format('M j, Y') }} to {{ $itinerary->end_date->format('M j, Y') }}?
                            This action cannot be undone.
                        </p>
                        <div class="flex justify-end gap-3">
                            <button @click="$dispatch('close-modal', { detail: 'delete-itinerary-{{ $itinerary->id }}' })"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded">Cancel</button>
                            <form method="POST" action="{{ route('itineraries.destroy', $itinerary->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Delete</button>
                            </form>
                        </div>
                    </x-slot>
                </x-modal>
            </div>
        @endif
    </div>

    @if($itinerary->groupMembers->count())
        <x-group-member-list :members="$itinerary->groupMembers" />
    @else
        <p class="text-sm text-gray-400 italic">No group members yet.</p>
    @endif

    <button @click="$dispatch('open-modal', { detail: 'add-member-{{ $itinerary->id }}' })"
        class="mt-2 px-3 py-1 bg-primary hover:bg-primary-light text-white rounded text-sm">
        + Add Member
    </button>

    <x-modal name="add-member-{{ $itinerary->id }}">
        <x-slot name="content">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Add Member</h2>
            @include('group-member.form', ['itinerary' => $itinerary])
            <div class="text-right mt-2">
                <button @click="$dispatch('close-modal', { detail: 'add-member-{{ $itinerary->id }}' })"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded">
                    Close
                </button>
            </div>
        </x-slot>
    </x-modal>

    <!-- ── Bookings List ──────────────────────────────────────────── -->
    @if($itinerary->bookings->count())
        <x-booking-list :bookings="$itinerary->bookings" />
    @else
        <p class="text-sm text-gray-400 italic">No bookings yet.</p>
    @endif

    <!-- ── Add-Booking Button ─────────────────────────────────────── -->
    <x-primary-button type="button" class="text-xs" @click="booking = {}; openBookingForm = true; $dispatch('open-modal', { detail: 'add-booking-{{ $itinerary->id }}' })">
        + Add Booking
    </x-primary-button>

    <!-- ── Add-Booking Modal ──────────────────────────────────────── -->
    <x-modal name="add-booking-{{ $itinerary->id }}">
        <x-slot name="content">
            @include('booking.booking-form', ['itinerary' => $itinerary, 'closeEvent' => 'add-booking-' . $itinerary->id])
        </x-slot>
    </x-modal>

    <!-- ── Edit-Booking Modal ─────────────────────────────────────── -->
    <x-modal name="edit-booking-{{ $itinerary->id }}">
        <x-slot name="content">
            @include('booking.booking-form', ['itinerary' => $itinerary, 'closeEvent' => 'edit-booking-' . $itinerary->id])
        </x-slot>
    </x-modal>

    <!-- ── Activities List ──────────────────────────────────────────── -->
    @if($activities->count())
        <x-activity-list :activities="$activities" />
    @else
        <p class="text-sm text-gray-400 italic">No activities yet.</p>
    @endif

    <!-- ── Add-Activity Button ──────────────────────────────────────── -->
    <div class="flex gap-2">
        <x-primary-button type="button" class="text-xs" @click="activity = {}; openActivityForm = true; $dispatch('open-modal', { detail: 'add-activity-{{ $itinerary->id }}' })">
            + Add Activity
        </x-primary-button>
        <a href="{{ route('itineraries.budgets.index', $itinerary->id) }}"
            class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-md">
            Budget
        </a>
    </div>

    <!-- ── Add-Activity Modal ───────────────────────────────────────── -->
    <x-modal name="add-activity-{{ $itinerary->id }}">
        <x-slot name="content">
            @include('activity.activity-form', ['itinerary' => $itinerary, 'closeEvent' => 'add-activity-' . $itinerary->id])
        </x-slot>
    </x-modal>

    <!-- ── Edit-Activity Modal (shared scope) ───────────────────────── -->
    <x-modal name="edit-activity-{{ $itinerary->id }}">
        <x-slot name="content">
            @include('activity.activity-form', ['itinerary' => $itinerary, 'closeEvent' => 'edit-activity-' . $itinerary->id])
        </x-slot>
    </x-modal>

    <!-- ── Map (hidden while any modal is open) ─────────────────────── -->
    <div x-show="!openActivityForm && !openEditModal && !openBookingForm && !openBookingEditModal" x-transition.opacity>
        <x-itinerary-map :activities="$mapActivities" :bookings="$itinerary->bookings" />
    </div>

</div>

