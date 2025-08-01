<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Entry
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        <form method="POST" action="{{ route('budgets.update', $budgetEntry->id) }}" class="space-y-2">
            @csrf
            @method('PUT')
            <input type="text" name="description" value="{{ old('description', $budgetEntry->description) }}" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $budgetEntry->amount) }}" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            <input type="date" name="entry_date" value="{{ old('entry_date', $budgetEntry->entry_date) }}" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            <input type="text" name="category" value="{{ old('category', $budgetEntry->category) }}" class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            <div class="text-right">
                <button class="px-3 py-1 bg-primary hover:bg-primary-dark text-white rounded text-sm">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
