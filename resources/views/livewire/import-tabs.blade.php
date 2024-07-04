<div class="min-w-fit">
    <nav class="flex bg-gray-100 list-none">
        {{-- <li class="import-nav-item">
            <a id="tab-file" class="import-nav-link @if($activeTab === 'file') active border-solid border-b-2 border-b-blue-500 @endif" href="#" wire:click.prevent="setActiveTab('file')">Upload File</a>
        </li> --}}
        <li class="import-nav-item">
            <a id="tab-workday" class="import-nav-link @if($activeTab === 'workday') active border-solid border-b-2 border-b-blue-500 @endif" href="#" wire:click.prevent="setActiveTab('workday')">Add Course (Workday)</a>
        </li>
        <li class="import-nav-item">
            <a id="tab-sei" class="import-nav-link @if($activeTab === 'sei') active border-solid border-b-2 border-b-blue-500 @endif" href="#" wire:click.prevent="setActiveTab('sei')">Add SEI Data</a>
        </li>
    </nav>

    <div class="bg-gray-50 mt-5">
        @if ($activeTab === 'file')
            @livewire('import-file')
        @elseif ($activeTab === 'sei')
            @livewire('import-sei-form')
        @elseif ($activeTab === 'workday')
            @livewire('import-workday-form')
        @endif
    </div>
</div>