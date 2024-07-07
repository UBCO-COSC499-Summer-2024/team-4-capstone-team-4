<x-app-layout>
    <div class="content">
        <h1 class="content-title nos">
            <span class="content-title-text">{{ __('Staff') }}</span>
        </h1>
        <div class="px-4">
            @livewire('staff-list-edit-mode')
        </div>
    </div>
</x-app-layout>