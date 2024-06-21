<nav class="">
    <ul class="flex">
        <li class="import-nav-item">
            <a class="import-nav-link @if($activeTab === 'file') active bg-red-500 @endif" href="#" wire:click.prevent="setActiveTab('file')">Upload File</a>
        </li>
        <li class="import-nav-item">
            <a class="import-nav-link @if($activeTab === 'sei') active bg-red-500 @endif" href="#" wire:click.prevent="setActiveTab('sei')">Insert SEI Data</a>
        </li>
        <li class="import-nav-item">
            <a class="import-nav-link @if($activeTab === 'workday') active bg-red-500 @endif" href="#" wire:click.prevent="setActiveTab('workday')">Insert Workday Data</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        @if ($activeTab === 'file')
            @livewire('import-file')
        @elseif ($activeTab === 'sei')
            @livewire('import-sei-form')
        @elseif ($activeTab === 'workday')
            @livewire('import-workday-form')
        @endif
    </div>
</nav>