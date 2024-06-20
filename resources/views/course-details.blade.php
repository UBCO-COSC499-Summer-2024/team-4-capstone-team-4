<x-app-layout>
    <div class="content">
        <h1>{{ __('Department Details') }}</h1>
        <section class="container">
                <ol class="instructor-list">
                    @foreach ($users as $user)
                            <a href="#" class="instructor-link" data-id="{{$user->id}}">{{ $user->firstname }} {{$user->lastname}}</a>
                    @endforeach
                </ol>
        </section>
        <section class="coursedetails-table">
           <x-coursedetails-table :data="$tableData"/>
        </section>
    </div>
    @vite('resources/js/course-details.js')
</x-app-layout>