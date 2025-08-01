@props(['itinerary'])
<form method="POST" action="{{ route('itineraries.group-members.store', $itinerary->id) }}" class="space-y-2">
    @csrf
    <input type="hidden" name="itinerary_id" value="{{ $itinerary->id }}">
    <div>
        <input type="text" name="name" placeholder="Name" required
               class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
    </div>
    <div>
        <input type="text" name="notes" placeholder="Notes"
               class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
    </div>
    <div class="text-right">
        <x-primary-button type="submit" class="text-xs">Add</x-primary-button>
    </div>
</form>
