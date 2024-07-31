<div>
    
        @if($duplicateCourses)
            @foreach($duplicateCourses as $course)
                <div>{{$course->prefix}}</div>
            @endforeach
        @endif

        <div>
            <button wire:click="userConfirmDuplicate">Confirm Duplicate?</button>
            <button wire:click="closeConfirmModal">Close</button>
        </div>

</div>