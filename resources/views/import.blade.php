<x-app-layout>
  <div class="content container mt-5">
    <h1>{{ __('Import Data') }}</h1>
    {{-- <x-import-form />
    <x-import-modal /> --}}

    @livewire('import-tabs')
  </div>
</x-app-layout>
