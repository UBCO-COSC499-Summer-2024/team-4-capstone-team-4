<div class="staff-preview">
    <ul class="staff-list">
        @foreach($user as $users)
            <li class="staff-item">
                <a href="#" class="staff-link" data-name="{{$users->name}}" data-duration="{{$users->duration}}" data-enrolled="{{ $users->enrolled }}" data-dropped="{{ $users->dropped }}" data-capacity="{{ $users->capacity }}">{{ $users->name }}</a>
            </li>
        @endforeach
    </ul>
</div>

