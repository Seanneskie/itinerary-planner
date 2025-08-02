<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $itinerary->title }} - Budget
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Add Budget Entry</h3>
            <form method="POST" action="{{ route('itineraries.budgets.store', $itinerary->id) }}" class="space-y-2">
                @csrf
                <input type="hidden" name="itinerary_id" value="{{ $itinerary->id }}">
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description <span class="text-red-500">*</span></label>
                    <input type="text" id="description" name="description" placeholder="Description" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Budget Amount <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" id="amount" name="amount" placeholder="Amount" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
                </div>
                <div>
                    <label for="entry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date <span class="text-red-500">*</span></label>
                    <input type="date" id="entry_date" name="entry_date" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Category</label>
                    <input type="text" id="category" name="category" placeholder="Category" class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
                </div>
                <div class="text-right">
                    <button class="px-3 py-1 bg-primary hover:bg-primary-dark text-white rounded text-sm">Add</button>
                </div>
            </form>
        </div>

        @if($categories->count())
            <form method="GET" class="text-right">
                <label class="mr-2 text-sm text-gray-700 dark:text-gray-300" for="category-filter">Filter:</label>
                <select id="category-filter" name="category" onchange="this.form.submit()" class="px-2 py-1 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected($category === $filter)>{{ $category }}</option>
                    @endforeach
                </select>
            </form>
        @endif

        @if($itinerary->budgetEntries->count())
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-semibold mb-3">Budget Entries</h3>
                    <table class="min-w-full overflow-hidden rounded-lg shadow divide-y divide-gray-300 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr class="text-left text-gray-700 dark:text-gray-200">
                                <th class="px-4 py-2 font-semibold uppercase tracking-wider">Description</th>
                                <th class="px-4 py-2 font-semibold uppercase tracking-wider">Budgeted</th>
                                <th class="px-4 py-2 font-semibold uppercase tracking-wider">Spent</th>
                                <th class="px-4 py-2 font-semibold uppercase tracking-wider">Date</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $groupedEntries = $itinerary->budgetEntries->sortBy('category')->groupBy('category');
                            @endphp
                            @foreach($groupedEntries as $category => $entries)
                                <tr class="bg-gray-200 dark:bg-gray-900">
                                    <th colspan="5" class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">{{ $category ?: 'Uncategorized' }}</th>
                                </tr>
                                @foreach($entries as $entry)
                                    <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700">
                                        <td class="px-4 py-2">{{ $entry->description }}</td>
                                        <td class="px-4 py-2 text-right">PHP{{ number_format($entry->amount, 2) }}</td>
                                        <td class="px-4 py-2 text-right">PHP{{ number_format($entry->spent_amount, 2) }}</td>
                                        <td class="px-4 py-2">{{ $entry->entry_date }}</td>
                                        <td class="px-4 py-2 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('budgets.edit', $entry->id) }}" class="inline-flex items-center px-2 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded text-xs">Edit</a>
                                                <a href="{{ route('budgets.edit-spent', $entry->id) }}" class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs">Update Spent</a>
                                                <form method="POST" action="{{ route('budgets.destroy', $entry->id) }}" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="space-y-6">
                    @php
                        $categoryTotals = $itinerary->budgetEntries->groupBy('category')->map->sum('spent_amount');
                    @endphp
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <h4 class="font-semibold mb-2">Summary</h4>
                        <p class="text-right font-semibold">Total Spent: PHP{{ number_format($itinerary->budgetEntries->sum('spent_amount'), 2) }}</p>
                        <ul class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            @foreach($categoryTotals as $category => $total)
                                <li>{{ $category }}: PHP{{ number_format($total, 2) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <h4 class="font-semibold mb-2">Spending Over Time</h4>
                        <x-budget-chart :entries="$itinerary->budgetEntries" />
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <h4 class="font-semibold mb-2">By Category</h4>
                        <x-budget-category-chart :entries="$itinerary->budgetEntries" />
                    </div>
                </div>
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400">No budget entries yet.</p>
        @endif
    </div>
</x-app-layout>
