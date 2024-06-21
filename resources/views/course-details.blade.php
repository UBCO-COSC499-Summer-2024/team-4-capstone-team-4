<x-app-layout>
    <div class="content">
        <h1>{{ __('Department Details') }}</h1>
        <section class="container">
                <ol class="instructor-list">
                    @foreach ($users as $user)
                    <li>
                            <a href="#" class="instructor-link" data-id="{{$user->id}}">{{ $user->firstname }} {{$user->lastname}}</a>
                    </li>
                    @endforeach
                </ol>
        </section>
        <section class="coursedetails-table">
          <table class="table">
            <thead>
                <tr>
                    <th class="sortable">Course Name</th>
                    <th class="sortable">Course Duration</th>
                    <th class="sortable">Enrolled Students</th>
                    <th class="sortable">Dropped Students</th>
                    <th class="sortable">Course Capacity</th>
                </tr>
            </thead>
            <tbody>
                <!--Table details here-->
            </tbody>
          </table>
        </section>
    </div>
    @vite('resources/js/course-details.js')
</x-app-layout>