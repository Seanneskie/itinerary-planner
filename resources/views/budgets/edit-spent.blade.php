<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Update Spent Amount
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        <form method="POST" action="{{ route('budgets.update-spent', $budgetEntry->id) }}" class="space-y-2">
            @csrf
            @method('PATCH')
            <div>
                <label for="spent_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Spent Amount <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" id="spent_amount" name="spent_amount" value="{{ old('spent_amount', $budgetEntry->spent_amount) }}" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            </div>
            <div class="text-right">
                <button class="px-3 py-1 bg-primary hover:bg-primary-dark text-white rounded text-sm">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
