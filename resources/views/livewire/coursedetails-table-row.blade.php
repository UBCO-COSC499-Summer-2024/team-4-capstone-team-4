<tr class="svcr-list-item" data-id="{{ $id }}"
x-data="{
    isEditing: @entangle('isEditing'),
    selected: @entangle('selected'),
    id: '{{ $id }}'
}">
    <td class="svcr-list-item-cell" data-column="select">
        <input type="checkbox" wire:model="selected" value="{{ $id }}" class="form-checkbox" @change="selected = !selected">
    </td>
    <td class="svcr-list-item-cell" data-column="courseName">
        <div class="svcr-list-item-td">{{ $courseName }}
        </div>
        {{-- <input x-show="isEditing" type="text" wire:model="courseName" class="svcr-list-item-edit" /> --}}
    </td>
    <td class="svcr-list-item-cell" data-column="departmentName">
        <div x-show="!isEditing" class="svcr-list-item-td">{{ $departmentName }}
        </div>
        <select x-show="isEditing" x-cloak wire:model="departmentId" class="form-select">
            @foreach ($areas as $department)
                <option value="{{ $department->id }}"
                    {{ $departmentId == $department->id ? 'selected' : '' }}
                    >{{ $department->name }}</option>
            @endforeach
        </select>
        @error('departmentId') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="instructorName">
        <div class="svcr-list-item-td">{{ $instructorName }}
        </div>
        {{-- <input x-show="isEditing" type="text" wire:model="instructorName" class="svcr-list-item-edit" /> --}}
    </td>
    <td class="svcr-list-item-cell" data-column="enrolledStudents">
        <div x-show="!isEditing" x-cloak class="svcr-list-item-td">{{ $enrolledStudents }}
        </div>
        <input x-show="isEditing" x-cloak type="number" wire:model="enrolledStudents" class="svcr-list-item-edit" />
        @error('enrolledStudents') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="droppedStudents">
        <div x-show="!isEditing" x-cloak class="svcr-list-item-td">{{ $droppedStudents }}
        </div>
        <input x-show="isEditing" x-cloak type="number" wire:model="droppedStudents" class="svcr-list-item-edit" />
        @error('droppedStudents') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="courseCapacity">
        <div x-show="!isEditing" x-cloak class="svcr-list-item-td">{{ $courseCapacity }}
        </div>
        <input x-show="isEditing" x-cloak type="number" wire:model="courseCapacity" class="svcr-list-item-edit" />
        @error('courseCapacity') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="room">
        <div x-show="!isEditing" x-cloak class="svcr-list-item-td">{{ $room }}</div>
        <input x-show="isEditing" x-cloak type="text" wire:model="room" class="svcr-list-item-edit" />
        @error('room') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="timings">
        <div class="svcr-list-item-td">{{ $timings }}
            {{-- <input x-show="isEditing" type="text" wire:model="timings" class="svcr-list-item-edit" /> --}}</div>
    </td>
    <td class="svcr-list-item-cell" data-column="seiData">
        <div class="svcr-list-item-td">{{ $seiData }}
            {{-- <input x-show="isEditing" type="text" wire:model="seiData" class="svcr-list-item-edit" /> --}}
        </div>
    </td>
    @if($canEdit)
    <td class="svcr-list-item-cell" data-column="actions">
        <div class="flex items-center svcr-list-item-actions">
            <button x-show="!isEditing" x-cloak @click.prevent="isEditing = true" class="svcr-list-item-action">
                <span class="material-symbols-outlined icon">edit</span>
            </button>
            <button x-show="isEditing" x-cloak @click.prevent="isEditing = false" class="svcr-list-item-action">
                <span class="material-symbols-outlined icon">cancel</span>
            </button>
            <button x-show="isEditing" x-cloak wire:click.prevent="$call('saveItem', {{$id}})" class="svcr-list-item-action">
                <span class="material-symbols-outlined icon">save</span>
            </button>
            {{-- archive --}}
            {{-- <button x-show="!isEditing" x-cloak wire:click.prevent="$call('archiveCourse', {{ $id }})" class="svcr-list-item-action">
                <span class="material-symbols-outlined icon">archive</span>
            </button> --}}
        </div>
    </td>
    @endif
</tr>
