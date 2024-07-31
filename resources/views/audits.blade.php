<x-app-layout>
    @vite(['resources/css/audits.css'])
    <div class="content">
        <h1 class="content-title">
            <span class="content-title-text">
                {{ __('Audit Logs') }}
            </span>
        </h1>

        <livewire:audit-logs-container />
    </div>
</x-app-layout>
