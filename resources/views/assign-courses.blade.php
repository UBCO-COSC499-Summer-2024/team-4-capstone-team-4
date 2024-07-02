<x-app-layout>
  <div class="content container mt-5">
    <h1>{{ __('Assign Instructors') }}</h1>
    {{-- <x-import-form />
    <x-import-modal /> --}}

    @livewire('import-assign-course')
  </div>
</x-app-layout>
