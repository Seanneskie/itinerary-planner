<x-app-layout title="{{ __('Add Itinerary') }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Itinerary') }}
        </h2>
    </x-slot>

    <div class="py-10 max-w-2xl mx-auto sm:px-6 lg:px-8" x-data="{ open: false }">
        <button @click="open = !open"
            class="mb-4 px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded">
            <span x-show="!open">Show Form</span>
            <span x-show="open">Hide Form</span>
        </button>

        <form method="POST" action="{{ route('itineraries.store') }}" x-show="open" x-transition enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-200">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-900 dark:text-white" />
            </div>

            <div class="mb-4">
                <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-200">Description</label>
                <textarea name="description" id="description"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-900 dark:text-white"></textarea>
            </div>

            <div class="mb-4">
                <label for="start_date" class="block font-medium text-sm text-gray-700 dark:text-gray-200">Start Date <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" id="start_date" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-900 dark:text-white" />
            </div>

            <div class="mb-4">
                <label for="end_date" class="block font-medium text-sm text-gray-700 dark:text-gray-200">End Date <span class="text-red-500">*</span></label>
            <input type="date" name="end_date" id="end_date" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-900 dark:text-white" />
            </div>

            <div class="mb-4">
                <label for="photo" class="block font-medium text-sm text-gray-700 dark:text-gray-200">Photo</label>
                <input type="file" name="photo" id="photo"
                    class="mt-1 block w-full text-gray-900 dark:text-white" />
            </div>

            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                Save Itinerary
            </button>
        </form>
    </div>
</x-app-layout>
