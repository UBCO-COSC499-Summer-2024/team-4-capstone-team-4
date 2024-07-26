<div class="relative min-w-fit text-sm font-medium  text-gray-500">
    <nav class="flex flex-wrap -mb-px list-none border-b border-gray-200">
        {{-- <li class="me-2">
            <a id="tab-file" class="import-nav-link @if($activeTab === 'file') active border-solid border-b-blue-500 hover:border-b-blue-500 @endif" href="#" wire:click.prevent="setActiveTab('file')">Upload File</a>
        </li> --}}
        <li class="me-2">
            <a id="tab-workday" class="import-nav-link @if($activeTab === 'workday') active text-[#3b4779] hover:text-[#3b4779] border-solid border-b-[#3b4779] hover:border-b-[#3b4779] @endif" href="#" wire:click.prevent="setActiveTab('workday')">Add Course Section</a>
        </li>
        <li class="me-2">
            <a id="tab-sei" class="import-nav-link @if($activeTab === 'sei') active text-[#3b4779] hover:text-[#3b4779] border-solid border-b-[#3b4779] hover:border-b-[#3b4779] @endif" href="#" wire:click.prevent="setActiveTab('sei')">Add SEI Data</a>
        </li>
        @if($activeTab === 'workday')
        <div class="absolute right-0 top-0">
            <button class="bg-white text-[#3b4779] border border-[#3b4779] px-3 py-2 mx-2 rounded-lg hover:bg-[#3b4779] hover:text-white" 
                onclick="location.href='{{ route('upload.file.show.workday') }}'">
                <span class="material-symbols-outlined">upload</span>
                Upload File
            </button>
        </div>
        @endif
        @if($activeTab === 'sei')
        <div class="absolute right-0 top-0">
            <button class="bg-white text-[#3b4779] border border-[#3b4779] px-3 py-2 mx-2 rounded-lg hover:bg-[#3b4779] hover:text-white" 
                onclick="location.href='{{ route('upload.file.show.sei') }}'">
                <span class="material-symbols-outlined">upload</span>
                Upload File
            </button>
        </div>
        @endif
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