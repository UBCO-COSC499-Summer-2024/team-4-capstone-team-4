<div class="flex items-center space-x-2">
    <form action="{{ route('staff') }}" method="GET" class="flex items-center">
        @csrf
        <input type="text" id="search-staff" name="search-staff" value="{{ request()->input('search-staff')}}" class="block p-2 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for staff">
        <button type="submit" class=" hidden ml-2 bg-blue-600 text-white px-3 py-1 rounded">Search</button>
    </form>
</div>