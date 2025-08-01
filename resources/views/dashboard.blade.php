<x-app-layout x-data="{ openForm: false }">
    <x-slot name="header">
        <div x-data="{ openForm: false }" class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Your Itineraries') }}
            </h2>
            <button @click="openForm = true" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded">
                + Add Itinerary
            </button>

            <!-- Modal -->
            <div x-show="openForm" x-transition x-cloak
                class="fixed inset-0 z-40 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-xl mx-auto"
                    @click.away="openForm = false">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">New Itinerary</h3>

                    <form method="POST" action="{{ route('itineraries.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="title"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Title</label>
                            <input type="text" name="title" id="title" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-white" />
                        </div>

                        <div class="mb-4">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                            <textarea name="description" id="description"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-white"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="start_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Start Date</label>
                            <input type="date" name="start_date" id="start_date" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-white" />
                        </div>

                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">End
                                Date</label>
                            <input type="date" name="end_date" id="end_date" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-white" />
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" @click="openForm = false"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow">
                                Save Itinerary
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @foreach ($itineraries as $itinerary)
            <x-itinerary-card :itinerary="$itinerary" />

        @endforeach
    </div>


</x-app-layout>