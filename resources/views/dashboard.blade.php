<x-app-layout>
    <div class="content">
        <h1>{{ __('Dashboard') }}</h1>
        <section class="dash-top">
            <x-chart :chart="$chart2"/>
            <x-chart :chart="$chart1"/>
        </section>
        <section class="dash-bottom">
            <x-instructor-lists />
        </section>
    </div>
</x-app-layout>
