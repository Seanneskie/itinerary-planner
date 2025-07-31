<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Itinerary') }}
        </h2>
    </x-slot>

    <div class="py-10 max-w-2xl mx-auto sm:px-6 lg:px-8" x-data="{ open: false }">
        <button @click="open = !open"
            class="mb-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
            <span x-show="!open">Show Form</span>
            <span x-show="open">Hide Form</span>
        </button>

        <form method="POST" action="{{ route('itineraries.store') }}" x-show="open" x-transition>
            @csrf

            <div class="mb-4">
                <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-200">Title</label>
                <input type="text" name="title" id="title" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-900 dark:text-white" />
            </div>

            <div class="mb-4">
                <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-200">Description</label>
                <textarea name="description" id="description"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-900 dark:text-white"></textarea>
            </div>

            <div class="mb-4">
                <label for="start_date" class="block font-medium text-sm text-gray-700 dark:text-gray-200">Start Date</label>
                <input type="date" name="start_date" id="start_date" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-900 dark:text-white" />
            </div>

            <div class="mb-4">
                <label for="end_date" class="block font-medium text-sm text-gray-700 dark:text-gray-200">End Date</label>
                <input type="date" name="end_date" id="end_date" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-900 dark:text-white" />
            </div>

            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                Save Itinerary
            </button>
        </form>
    </div>
</x-app-layout>
