<div class="staff-preview">
    <ul class="staff-list">
        @foreach($courses as $course)
            <li class="staff-item">
                <a href="#" class="staff-link" data-name="{{$course->name}}" data-duration="{{$course->duration}}" data-enrolled="{{ $course->enrolled }}" data-dropped="{{ $course->dropped }}" data-capacity="{{ $course->capacity }}">{{ $course->name }}</a>
            </li>
            </li>
        @endforeach
    </ul>
</div>
