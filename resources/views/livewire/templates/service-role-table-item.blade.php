<tr class="svcr-list-item">
    <td class="svcr-list-item-cell" data-column="id">
        {{ $id }}
    </td>
    <td class="svcr-list-item-cell" data-column="name">
        <input type="text" class="svcr-list-item-edit" wire:model="name" placeholder="ex. Student Advisor">
        @error('name') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="description">
        <input type="text" class="svcr-list-item-edit" wire:model="description" placeholder="Write something...">
        @error('description') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="room">
        <div class="flex items-center justify-start gap-2">
            <input type="text" class="svcr-list-item-edit !min-w-8 !max-w-20"
                wire:model="room"
                hidden
                value="{{ $room }}"
                x-cloak>
            <input type="text" class="svcr-list-item-edit !min-w-8 !max-w-12"
                wire:model="roomB"
                placeholder="LIB"
                value="{{ $roomB }}">
            <input type="text" class="svcr-list-item-edit !min-w-8 !max-w-12"
                wire:model="roomN"
                placeholder="123"
                value="{{ $roomN }}">
            <input type="text" class="svcr-list-item-edit !min-w-4 !max-w-8"
                wire:model="roomS"
                placeholder="B"
                value="{{ $roomS }}">
        </div>
        @error('room') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="area">
        <select class="svcr-list-item-edit" wire:model="area_id">
            <option value="">Select Area</option>
            @foreach ($areas as $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
        @error('area_id') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="year">
        <div class="form-group !max-w-20 !w-fit">
            <input type="number" class="svcr-list-item-edit !min-w-16 !w-fit" wire:model="year" placeholder="ex. {{date('Y')}}">
            @error('year') <span class="error">{{ $message }}</span> @enderror
        </div>
    </td>
    <td class="svcr-list-item-cell" data-column="monthly_hours">
        <div
            {{-- tailwind grid 6 items each row --}}
            class="grid grid-cols-12 grid-rows-1 gap-2"
            >
            @php
                // dd($svcrole);
                $monthly = json_decode($svcrole['monthly_hours'], true);
            @endphp
            @foreach ($monthly as $month => $hour)
                <div class="form-group">
                    <input type="number" min="0" max="200"
                        id="monthly-hours-{{ $id }}-{{ $month }}"
                        class="svcr-list-item-edit !min-w-10 !max-w-16"
                        data-tippy-content="{{ $month }}"
                        wire:model="monthly_hrs.{{ $month }}"
                        value="{{ (int) $hour }}">
                    {{-- <label for="monthly-hours-{{ $id }}-{{ $month }}">{{ substr(Str::ucfirst($month), 0, 1) }}</label> --}}
                </div>
            @endforeach
        </div>
        @error('monthly_hrs.*') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="archived">
        <label class="switch">
            <input type="checkbox" wire:model="archived">
            <span class="slider round"></span>
        </label>
    </td>
    <td class="svcr-list-item-cell" data-column="requires_update">
        <label class="switch">
            <input type="checkbox" wire:model="requires_update" disabled>
            <span class="slider round"></span>
        </label>
    </td>
    <td class="svcr-list-item-cell" data-column="actions">
        <div class="svcr-list-item-actions">
            <button class="svcr-list-item-action btn-danger" data-action="delete"
                    data-tippy-content="Delete this row"
                    wire:click="deleteItem({{ $id }})"
                    @if ($isSaved) disabled @endif>
                <span class="material-symbols-outlined">delete</span>
            </button>
        </div>
    </td>
</tr>
