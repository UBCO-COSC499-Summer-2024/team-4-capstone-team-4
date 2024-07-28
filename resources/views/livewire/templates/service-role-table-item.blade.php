<tr class="svcr-list-item">
    <td class="svcr-list-item-cell" data-column="id">
        {{ $id }}
    </td>
    <td class="svcr-list-item-cell" data-column="name">
        <input type="text" class="form-input" wire:model="name">
        @error('name') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="description">
        <input type="text" class="form-input" wire:model="description">
        @error('description') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="area">
        <select class="form-select" wire:model="area_id">
            <option value="">Select Area</option>
            @foreach ($areas as $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
        @error('area_id') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="year">
        <div class="form-group !max-w-20 !w-fit">
            <input type="number" class="form-input !min-w-16 !w-fit" wire:model="year">
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
                        class="form-input !min-w-10 !max-w-16"
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
