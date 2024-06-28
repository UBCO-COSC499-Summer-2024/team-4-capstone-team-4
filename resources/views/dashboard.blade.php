<x-app-layout>
    <div class="content">
        <h1>{{ __('Dashboard') }}</h1>
        <section class="dash-top">
            <x-instructor-target :chart1="$chart1" :chart2="$chart2" :chart3="$chart3"/>
        </section>
        <section class="dash-bottom">
            <x-instructor-lists />
        </section>
    </div>
</x-app-layout>
