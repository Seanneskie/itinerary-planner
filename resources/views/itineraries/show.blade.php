<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $itinerary->title }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <x-itinerary-card :itinerary="$itinerary" />

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Budget Overview</h3>
            @if($itinerary->budgetEntries->count())
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mb-4">
                    <thead>
                        <tr class="text-left">
                            <th class="py-2">Description</th>
                            <th class="py-2">Amount</th>
                            <th class="py-2">Date</th>
                            <th class="py-2">Category</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($itinerary->budgetEntries as $entry)
                            <tr>
                                <td class="py-2">{{ $entry->description }}</td>
                                <td class="py-2">${{ number_format($entry->amount, 2) }}</td>
                                <td class="py-2">{{ $entry->entry_date }}</td>
                                <td class="py-2">{{ $entry->category }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <x-budget-chart :entries="$itinerary->budgetEntries" />
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-4">
                    Total: ${{ number_format($itinerary->budgetEntries->sum('amount'), 2) }}
                </p>
                @if($averageBudget)
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Average budget for itineraries with activities in {{ $primaryLocation }}: ${{ number_format($averageBudget, 2) }}
                    </p>
                @endif
            @else
                <p class="text-gray-500 dark:text-gray-400">No budget entries yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>
