<x-app-layout title="Edit Entry">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Entry
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        <form method="POST" action="{{ route('budgets.update', $budgetEntry->id) }}" class="space-y-2">
            @csrf
            @method('PUT')
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description <span class="text-red-500">*</span></label>
                <input type="text" id="description" name="description" value="{{ old('description', $budgetEntry->description) }}" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            </div>
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Budget Amount <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount', $budgetEntry->amount) }}" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            </div>
            <div>
                <label for="entry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date <span class="text-red-500">*</span></label>
                <input type="date" id="entry_date" name="entry_date" value="{{ old('entry_date', $budgetEntry->entry_date) }}" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Category</label>
                <input type="text" id="category" name="category" value="{{ old('category', $budgetEntry->category) }}" class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            </div>
            <div class="text-right">
                <button class="px-3 py-1 bg-primary hover:bg-primary-dark text-white rounded text-sm">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
