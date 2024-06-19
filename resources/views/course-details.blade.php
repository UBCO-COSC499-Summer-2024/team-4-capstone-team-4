<x-app-layout>
    <div class="content">
        <h1>{{ __('Course Details') }}</h1>
        @foreach($courses as $course)
            <div class="course-details">
                <p><strong>Course Name:</strong> {{ $course->name }}</p>
                <p><strong>Course Duration:</strong> {{ $course->duration }}</p>
                <p><strong>Enrolled Students:</strong> {{ $course->enrolled }}</p>
                <p><strong>Dropped Students:</strong> {{ $course->dropped }}</p>
                <p><strong>Course Capacity:</strong> {{ $course->capacity }}</p>
                <section class="dash-bottom">
                    <x-staff-preview :course="$course"/>
                </section>
                <hr>
            </div>
        @endforeach
    </div>
</x-app-layout>