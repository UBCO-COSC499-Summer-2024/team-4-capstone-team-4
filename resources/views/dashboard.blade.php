<x-app-layout>
    <div class="content">
        <h1>{{ __('Dashboard') }}</h1>
        <section class="dash-top">
            <x-visualizations />
        </section>
        <section class="dash-bottom">
            <x-staff-preview />
        </section>
    </div>
</x-app-layout>
