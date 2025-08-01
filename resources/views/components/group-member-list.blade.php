@props(['members'])
<ul class="mt-4 space-y-2">
    @foreach($members as $member)
        <li x-data="{ openEdit: false, openDelete: false }" class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 rounded px-3 py-2">
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $member->name }}</p>
                @if($member->notes)
                    <p class="text-xs text-gray-500 dark:text-gray-300">{{ $member->notes }}</p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <button @click.prevent="openEdit = true"
                    class="inline-flex items-center px-2 py-1 bg-primary hover:bg-primary-dark text-white rounded text-xs">
                    Edit
                </button>
                <button @click="openDelete = true"
                    class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">
                    Delete
                </button>
            </div>

            <div x-show="openEdit" x-cloak x-transition.opacity.scale.80 class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Edit Member</h2>
                    <form method="POST" action="{{ route('group-members.update', $member->id) }}" class="space-y-2">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ $member->name }}" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
                        <input type="text" name="notes" value="{{ $member->notes }}" class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
                        <div class="text-right">
                            <button class="px-3 py-1 bg-primary hover:bg-primary-dark text-white rounded text-sm">Update</button>
                        </div>
                    </form>
                    <div class="text-right mt-2">
                        <button @click="openEdit = false" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded">Close</button>
                    </div>
                </div>
            </div>

            <div x-show="openDelete" x-cloak x-transition.opacity.scale.80 class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-sm">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-2">Confirm Delete</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        Are you sure you want to delete <span class="font-semibold">{{ $member->name }}</span>? This action cannot be undone.
                    </p>
                    <div class="flex justify-end gap-3">
                        <button @click="openDelete = false" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded">Cancel</button>
                        <form method="POST" action="{{ route('group-members.destroy', $member->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </li>
    @endforeach
</ul>
