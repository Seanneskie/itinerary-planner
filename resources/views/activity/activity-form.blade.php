{{-- resources/views/activity/activity-form.blade.php --}}
@props(['itinerary'])

<div  @click.away="openActivityForm = false"
      class="bg-white dark:bg-gray-800 rounded-xl shadow-lg ring-1 ring-gray-200 dark:ring-gray-700
             w-full max-w-lg p-6 space-y-4 text-sm">

    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">New Activity</h3>

    <form method="POST" action="{{ route('activities.store') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="itinerary_id" value="{{ $itinerary->id }}">

        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Title --}}
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" required
                       class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white
                              ring-1 ring-inset ring-gray-300 focus:ring-indigo-500 focus:ring-2 outline-none" />
            </div>

            {{-- Location Name --}}
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Location name</label>
                <input type="text" name="location"
                       class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white
                              ring-1 ring-inset ring-gray-300 focus:ring-indigo-500 focus:ring-2 outline-none" />
            </div>

            {{-- Map selector spans full width --}}
            <div class="sm:col-span-2 space-y-2">
                <label class="font-medium text-gray-700 dark:text-gray-300">Drop a pin</label>

                <div id="drop-pin-map"
                     class="h-48 w-full rounded-md overflow-hidden ring-1 ring-gray-300 dark:ring-gray-600"></div>

                <div class="grid grid-cols-2 gap-2">
                    <input type="text" id="latitude"  name="latitude"  readonly placeholder="Lat"
                           class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-inset ring-gray-300" />
                    <input type="text" id="longitude" name="longitude" readonly placeholder="Lng"
                           class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-inset ring-gray-300" />
                </div>
            </div>

            {{-- Date & time --}}
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Date &amp; time</label>
                <input type="datetime-local" name="scheduled_at" required
                       class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white
                              ring-1 ring-inset ring-gray-300 focus:ring-indigo-500 focus:ring-2 outline-none" />
            </div>

            {{-- Notes --}}
            <div class="flex flex-col space-y-1">
                <label class="font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea name="note" rows="1"
                          class="px-3 py-2 rounded-md resize-y bg-gray-50 dark:bg-gray-900 dark:text-white
                                 ring-1 ring-inset ring-gray-300 focus:ring-indigo-500 focus:ring-2 outline-none"></textarea>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end space-x-3">
            <button type="button"
                    @click="openActivityForm = false"
                    class="px-4 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                Cancel
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white shadow">
                Save
            </button>
        </div>
    </form>
</div>
