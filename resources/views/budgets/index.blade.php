<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $itinerary->title }} - Budget
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto space-y-6">
        <form method="POST" action="{{ route('itineraries.budgets.store', $itinerary->id) }}" class="space-y-2">
            @csrf
            <input type="hidden" name="itinerary_id" value="{{ $itinerary->id }}">
            <input type="text" name="description" placeholder="Description" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            <input type="number" step="0.01" name="amount" placeholder="Amount" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            <input type="date" name="entry_date" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            <input type="text" name="category" placeholder="Category" class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            <div class="text-right">
                <button class="px-3 py-1 bg-primary hover:bg-primary-dark text-white rounded text-sm">Add</button>
            </div>
        </form>

        @if($itinerary->budgetEntries->count())
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="text-left">
                        <th class="py-2">Description</th>
                        <th class="py-2">Amount</th>
                        <th class="py-2">Date</th>
                        <th class="py-2">Category</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($itinerary->budgetEntries as $entry)
                        <tr>
                            <td class="py-2">{{ $entry->description }}</td>
                            <td class="py-2">${{ number_format($entry->amount,2) }}</td>
                            <td class="py-2">{{ $entry->entry_date }}</td>
                            <td class="py-2">{{ $entry->category }}</td>
                            <td class="py-2 text-right">
                                <form method="POST" action="{{ route('budgets.destroy', $entry->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <x-budget-chart :entries="$itinerary->budgetEntries" />
        @else
            <p class="text-gray-500 dark:text-gray-400">No budget entries yet.</p>
        @endif
    </div>
</x-app-layout>
