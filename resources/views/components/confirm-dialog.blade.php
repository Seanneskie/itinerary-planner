@props(['name', 'title', 'message' => null])

<x-modal :name="$name" focusable>
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-2">{{ $title }}</h2>
        <div class="text-sm text-gray-600 dark:text-gray-300 mb-4">
            {{ $message ?? $slot }}
        </div>
        <div class="flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
            {{ $confirm }}
        </div>
    </div>
</x-modal>
