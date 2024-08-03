<div class="relative min-w-fit text-sm font-medium  text-gray-500">
    <nav class="flex flex-wrap -mb-px list-none border-b border-gray-200">
        <li class="me-2">
            <a id="tab-courses" class="import-nav-link @if($activeTab === 'courses') active text-[#3b4779] hover:text-[#3b4779] border-solid border-b-[#3b4779] hover:border-b-[#3b4779] @endif" href="#" wire:click.prevent="setActiveTab('courses')">Course Sections</a>
        </li>
        <li class="me-2">
            <a id="tab-tas" class="import-nav-link @if($activeTab === 'tas') active text-[#3b4779] hover:text-[#3b4779] border-solid border-b-[#3b4779] hover:border-b-[#3b4779] @endif" href="#" wire:click.prevent="setActiveTab('tas')">TAs</a>
        </li>
    </nav>

    <div class=" mt-5">
        @if ($activeTab === 'courses')
            @livewire('course-details')
        @else
            @livewire('ta-details')
        @endif
    </div>
</div>
