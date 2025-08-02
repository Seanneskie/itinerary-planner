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
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description <span class="text-red-500">*</span></label>
                <input type="text" id="description" name="description" placeholder="Description" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            </div>
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Amount <span class="text-red-500">*</span></label>
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
                            <td class="py-2">PHP{{ number_format($entry->amount, 2) }}</td>
                            <td class="py-2">{{ $entry->entry_date }}</td>
                            <td class="py-2">{{ $entry->category }}</td>
                            <td class="py-2 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('budgets.edit', $entry->id) }}" class="inline-flex items-center px-2 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded text-xs">Edit</a>
                                    <form method="POST" action="{{ route('budgets.destroy', $entry->id) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @php
                $categoryTotals = $itinerary->budgetEntries->groupBy('category')->map->sum('amount');
            @endphp
            <p class="text-right font-semibold mt-2">Total Spent: PHP{{ number_format($itinerary->budgetEntries->sum('amount'), 2) }}</p>
            <ul class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                @foreach($categoryTotals as $category => $total)
                    <li>{{ $category }}: PHP{{ number_format($total, 2) }}</li>
                @endforeach
            </ul>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-budget-chart :entries="$itinerary->budgetEntries" />
                <x-budget-category-chart :entries="$itinerary->budgetEntries" />
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400">No budget entries yet.</p>
        @endif
    </div>
</x-app-layout>
