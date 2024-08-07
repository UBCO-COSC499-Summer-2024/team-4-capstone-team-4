<div class="relative p-2 bg-white rounded-lg shadow-lg w-11/12 max-w-lg">
    <div class="flex flex-row justify-between items-center">
        <div class="m-2 text-2xl text-[#3b4779]">Find Instructor</div>
        <button class="absolute right-5 top-5 text-black font-bold">
            <span wire:click="closeInstructorModal" class="material-symbols-outlined">close</span>
        </button>
    </div>

    <input type="text" wire:model.live="searchTerm" wire:change="updateSearch" class="block my-2 w-full px-2 py-4 ps-10 text-md text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-[#3b4779] focus:border-blue-[#3b4779] dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-[#3b4779] dark:focus:border-blue-[#3b4779]"  placeholder="Search for an Instructor">

    <div class="max-h-96 overflow-y-auto">
     @foreach($filteredInstructors as $instructor)
        <button type="button" wire:click="selectInstructor({{ $instructor->id }}, '{{ $instructor->firstname }}', '{{ $instructor->lastname}}' ,{{ $selectedIndex }})" class="w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white hover:pointer">
            {{ $instructor->firstname }} {{ $instructor->lastname }}
        </button>
    @endforeach
    </div>
</div>