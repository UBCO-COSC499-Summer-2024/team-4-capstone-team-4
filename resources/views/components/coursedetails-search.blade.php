@props(['user'])

<div class="relative">
    <input type="text" data-route="{{ route('course-details', ['user' => $user->id]) }}" id="searchInput" class="border rounded px-4 py-2" placeholder="Search..."
    class="px-4 py-2 border border-gray-300 rounded-md" />
</div>
