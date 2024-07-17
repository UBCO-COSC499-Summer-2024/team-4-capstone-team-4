<div class="min-w-fit text-sm font-medium  text-gray-500">
    <nav class="flex flex-wrap -mb-px list-none border-b border-gray-200">
        {{-- <li class="me-2">
            <a id="tab-file" class="import-nav-link @if($activeTab === 'file') active border-solid border-b-blue-500 hover:border-b-blue-500 @endif" href="#" wire:click.prevent="setActiveTab('file')">Upload File</a>
        </li> --}}
        <li class="me-2">
            <a id="tab-workday" class="import-nav-link @if($activeTab === 'workday') active border-solid border-b-blue-500 hover:border-b-blue-500 @endif" href="#" wire:click.prevent="setActiveTab('workday')">Add Course (Workday)</a>
        </li>
        <li class="me-2">
            <a id="tab-sei" class="import-nav-link @if($activeTab === 'sei') active border-solid border-b-blue-500 hover:border-b-blue-500 @endif" href="#" wire:click.prevent="setActiveTab('sei')">Add SEI Data</a>
        </li>
    </nav>

    <div class=" mt-5">
        @if ($activeTab === 'file')
            {{-- @livewire('import-file') --}}
            {{-- <x-upload-file /> --}}
        @elseif ($activeTab === 'sei')
            @livewire('import-sei-form')
        @elseif ($activeTab === 'workday')
            @livewire('import-workday-form')
        @endif
    </div>
</div>