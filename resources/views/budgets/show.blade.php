<x-app-layout title="Budget Entry">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Budget Entry
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto space-y-4">
        <p><strong>Description:</strong> {{ $budgetEntry->description }}</p>
        <p><strong>Budgeted:</strong> PHP{{ number_format($budgetEntry->amount,2) }}</p>
        <p><strong>Spent:</strong> PHP{{ number_format($budgetEntry->spent_amount,2) }}</p>
        <p><strong>Date:</strong> {{ $budgetEntry->entry_date }}</p>
        <p><strong>Category:</strong> {{ $budgetEntry->category }}</p>
        @if($budgetEntry->participants)
            @php
                $members = $budgetEntry->itinerary->groupMembers->whereIn('id', $budgetEntry->participants);
            @endphp
            <p><strong>Participants:</strong></p>
            <ul class="list-disc ml-4">
                @foreach($members as $member)
                    <li>
                        {{ $member->name }}
                        @if(in_array($member->id, $budgetEntry->paid_participants ?? []))
                            <span class="text-green-600">(paid)</span>
                        @else
                            <span class="text-red-600">(unpaid)</span>
                        @endif
                    </li>
                @endforeach
            </ul>
            <p><strong>Per Person:</strong> PHP{{ number_format($budgetEntry->amount / max(count($budgetEntry->participants),1), 2) }}</p>
        @endif
    </div>
</x-app-layout>
