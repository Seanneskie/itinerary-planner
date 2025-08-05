@props(['members'])
@php $itemCount = count($members); @endphp
<div x-data="{ limit: 5, count: {{ $itemCount }} }">
<ul class="mt-4 space-y-2" id="group-member-list">
    @foreach($members as $member)
        @php
            $photo = $member->photo_path
                ? Storage::url($member->photo_path)
                : asset('images/default-photo.svg');
        @endphp
        <li x-data="{ openEdit: false, openDelete: false }" x-show="{{ $loop->index }} < limit" class="flex items-center justify-between bg-gray-100 dark:bg-gray-700 rounded px-3 py-2">
            <div class="flex items-center gap-3">
                <img src="{{ $photo }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $member->name }}</p>
                    @if($member->notes)
                        <p class="text-xs text-gray-500 dark:text-gray-300">{{ $member->notes }}</p>
                    @endif
                </div>
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
                    <form method="POST" action="{{ route('group-members.update', $member->id) }}" class="space-y-2" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="member-name-{{ $member->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name <span class="text-red-500">*</span></label>
                            <input type="text" id="member-name-{{ $member->id }}" name="name" value="{{ $member->name }}" required class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
                        </div>
                        <div>
                            <input type="text" name="notes" value="{{ $member->notes }}" class="w-full px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 dark:text-white ring-1 ring-gray-300">
                        </div>
                        <div>
                            <label for="member-photo-{{ $member->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Photo</label>
                            <input type="file" id="member-photo-{{ $member->id }}" name="photo" class="w-full text-gray-900 dark:text-white">
                        </div>
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
<button
    x-show="count > 5"
    @click="limit = limit === 5 ? count : 5"
    @keydown.enter.prevent="limit = limit === 5 ? count : 5"
    @keydown.space.prevent="limit = limit === 5 ? count : 5"
    :aria-expanded="limit > 5"
    aria-controls="group-member-list"
    :aria-label="limit === 5 ? 'Show more members' : 'Show less members'"
    class="mt-2 text-sm text-primary hover:underline">
    <span x-text="limit === 5 ? 'Show more' : 'Show less'"></span>
</button>
</div>
