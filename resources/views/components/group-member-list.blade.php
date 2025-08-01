@props(['members'])
<ul class="mt-4 space-y-2">
    @foreach($members as $member)
        <li class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 rounded px-3 py-2">
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $member->name }}</p>
                @if($member->notes)
                    <p class="text-xs text-gray-500 dark:text-gray-300">{{ $member->notes }}</p>
                @endif
            </div>
            <form method="POST" action="{{ route('group-members.destroy', $member->id) }}">
                @csrf
                @method('DELETE')
                <button class="text-red-600 text-xs">Remove</button>
            </form>
        </li>
    @endforeach
</ul>
