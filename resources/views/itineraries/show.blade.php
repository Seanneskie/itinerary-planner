<x-app-layout title="{{ $itinerary->title }}">
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
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <h4 class="text-md font-semibold mb-2">Entries</h4>
                        <table class="min-w-full mb-4 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr class="text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <th class="py-2 px-3">Description</th>
                                    <th class="py-2 px-3 text-right">Budgeted</th>
                                    <th class="py-2 px-3 text-right">Spent</th>
                                    <th class="py-2 px-3">Date</th>
                                    <th class="py-2 px-3">Category</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-600 dark:text-gray-300">
                                @foreach($itinerary->budgetEntries as $entry)
                                    <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-900' : '' }}">
                                        <td class="py-2 px-3">{{ $entry->description }}</td>
                                        <td class="py-2 px-3 text-right">PHP{{ number_format($entry->amount, 2) }}</td>
                                        <td class="py-2 px-3 text-right">PHP{{ number_format($entry->spent_amount, 2) }}</td>
                                        <td class="py-2 px-3">{{ $entry->entry_date->format('M j, Y') }}</td>
                                        <td class="py-2 px-3">{{ $entry->category }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-md font-semibold mb-2">Spending Over Time</h4>
                            <x-budget-chart :entries="$itinerary->budgetEntries" />
                        </div>
                        <div>
                            <h4 class="text-md font-semibold mb-2">By Category</h4>
                            <x-budget-category-chart :entries="$itinerary->budgetEntries" />
                        </div>
                    </div>
                </div>
                @php
                    $categoryTotals = $itinerary->budgetEntries->groupBy('category')->map->sum('spent_amount');
                    $topCategory = $categoryTotals->sortDesc()->keys()->first();
                    $totalBudget = $itinerary->budgetEntries->sum('amount');
                    $totalSpent = $itinerary->budgetEntries->sum('spent_amount');
                @endphp
                <div class="mt-6">
                    <h4 class="text-md font-semibold mb-2">Category Breakdown</h4>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-600 dark:text-gray-300">
                        <thead>
                            <tr class="text-left">
                                <th class="py-2">Category</th>
                                <th class="py-2">Spent</th>
                                <th class="py-2">Percent</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($categoryTotals as $category => $total)
                                <tr @if($category === $topCategory) class="font-semibold" @endif>
                                    <td class="py-2">{{ $category }}</td>
                                    <td class="py-2">PHP{{ number_format($total, 2) }}</td>
                                    <td class="py-2">{{ $totalSpent > 0 ? round($total / $totalSpent * 100, 1) : 0 }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                    <p>Top spending category: {{ $topCategory }} (PHP{{ number_format($categoryTotals[$topCategory], 2) }})</p>
                    <p>Total budget: PHP{{ number_format($totalBudget, 2) }}</p>
                    <p>Total spent: PHP{{ number_format($totalSpent, 2) }}</p>
                    @if($averageBudget)
                        <p>
                            Average budget for itineraries with activities in {{ $primaryLocation }}: PHP{{ number_format($averageBudget, 2) }}
                            ({{ round(($totalSpent - $averageBudget) / $averageBudget * 100, 1) }}% {{ $totalSpent >= $averageBudget ? 'above' : 'below' }} average)
                        </p>
                    @endif
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">No budget entries yet.</p>
            @endif
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('itineraries.export.excel', $itinerary) }}" class="px-4 py-2 bg-green-600 text-white rounded">Export to Excel</a>
            <a href="{{ route('itineraries.export.pdf', $itinerary) }}" class="px-4 py-2 bg-red-600 text-white rounded">Export to PDF</a>
        </div>
    </div>
</x-app-layout>
