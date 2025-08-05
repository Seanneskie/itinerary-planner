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
                <input type="date" id="entry_date" name="entry_date" value="{{ old('entry_date', $budgetEntry->entry_date?->format('Y-m-d')) }}" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Category</label>
                <input type="text" id="category" name="category" value="{{ old('category', $budgetEntry->category) }}" class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
            </div>
            @if($budgetEntry->itinerary->groupMembers->count())
                <div>
                    <span class="block text-sm font-medium text-gray-700 dark:text-gray-200">Split with</span>
                    <div class="flex flex-wrap gap-2 mt-1">
                        @foreach($budgetEntry->itinerary->groupMembers as $member)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="participants[]" value="{{ $member->id }}" @checked(in_array($member->id, old('participants', $budgetEntry->participants ?? []))) class="rounded">
                                <span class="ml-1 text-sm">{{ $member->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <label class="inline-flex items-center mt-2">
                        <input type="checkbox" id="toggle-all" class="rounded">
                        <span class="ml-1 text-sm">Include all</span>
                    </label>
                </div>
                <script>
                    document.getElementById('toggle-all')?.addEventListener('change', function () {
                        document.querySelectorAll('input[name="participants[]"]').forEach(cb => cb.checked = this.checked);
                    });
                </script>
            @endif
            <div class="text-right">
                <button class="px-3 py-1 bg-primary hover:bg-primary-dark text-white rounded text-sm">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
