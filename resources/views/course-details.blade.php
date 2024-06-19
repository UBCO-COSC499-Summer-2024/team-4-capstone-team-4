<x-app-layout>
    <div class="content">
        <h1>{{ __('Department Details') }}</h1>
        <section class="container">
            <section class="dash-bottom">
                <ol class="instructor-list">
                    @foreach ($users as $user)
                            <a href="#" class="instructor-link" >{{ $user->firstname }} {{$user->lastname}}</a>
                    @endforeach
                </ol>
            </section>
        </section>
        <section class="coursedetails-table">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Duration</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled Students</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dropped Students</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Capacity</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="course-details-table-body">
                    @vite('resources/js/course-details.js')
                </tbody>
            </table>
        </section>
    </div>
</x-app-layout>