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
                                <td class="py-2">PHP{{ number_format($entry->amount, 2) }}</td>
                                <td class="py-2">{{ $entry->entry_date }}</td>
                                <td class="py-2">{{ $entry->category }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <x-budget-chart :entries="$itinerary->budgetEntries" />
                <x-budget-category-chart :entries="$itinerary->budgetEntries" />
                @php
                    $categoryTotals = $itinerary->budgetEntries->groupBy('category')->map->sum('amount');
                    $topCategory = $categoryTotals->sortDesc()->keys()->first();
                    $totalSpent = $itinerary->budgetEntries->sum('amount');
                @endphp
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-4 text-sm text-gray-600 dark:text-gray-300">
                    <thead>
                        <tr class="text-left">
                            <th class="py-2">Category</th>
                            <th class="py-2">Amount</th>
                            <th class="py-2">Percent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($categoryTotals as $category => $total)
                            <tr @if($category === $topCategory) class="font-semibold" @endif>
                                <td class="py-2">{{ $category }}</td>
                                <td class="py-2">PHP{{ number_format($total, 2) }}</td>
                                <td class="py-2">{{ round($total / $totalSpent * 100, 1) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-4">
                    Top spending category: {{ $topCategory }} (PHP{{ number_format($categoryTotals[$topCategory], 2) }})
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-4">
                    Total: PHP{{ number_format($totalSpent, 2) }}
                </p>
                @if($averageBudget)
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Average budget for itineraries with activities in {{ $primaryLocation }}: PHP{{ number_format($averageBudget, 2) }}
                        ({{ round(($totalSpent - $averageBudget) / $averageBudget * 100, 1) }}% {{ $totalSpent >= $averageBudget ? 'above' : 'below' }} average)
                    </p>
                @endif
            @else
                <p class="text-gray-500 dark:text-gray-400">No budget entries yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>
