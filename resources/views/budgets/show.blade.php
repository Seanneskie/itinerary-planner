<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Budget Entry
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto space-y-4">
        <p><strong>Description:</strong> {{ $budgetEntry->description }}</p>
        <p><strong>Amount:</strong> PHP{{ number_format($budgetEntry->amount,2) }}</p>
        <p><strong>Date:</strong> {{ $budgetEntry->entry_date }}</p>
        <p><strong>Category:</strong> {{ $budgetEntry->category }}</p>
    </div>
</x-app-layout>
