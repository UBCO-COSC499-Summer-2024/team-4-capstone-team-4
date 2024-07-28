<tr class="svcr-list-item">
    <td class="svcr-list-item-cell" data-column="name">
        <input type="text" class="form-item" wire:model="svcrole.name">
        @error('svcrole.name') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="description">
        <input type="text" class="form-item" wire:model="svcrole.description">
        @error('svcrole.description') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="area">
        <select class="form-item" wire:model="svcrole.area_id">
            <option value="">Select Area</option>
            @foreach ($areas as $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
        @error('svcrole.area_id') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="year">
        <input type="number" class="form-item" wire:model="svcrole.year">
        @error('svcrole.year') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="monthly_hours">
        <div
            {{-- tailwind grid 6 items each row --}}
            class="grid grid-cols-6 gap-4"
            >
            @foreach ($monthly_hrs as $month => $hour)
                <div class="form-item">
                    <label for="monthly-hours-{{ $id }}-{{ $month }}">{{ Str::ucfirst($month) }}:</label>
                    <input type="number" min="0"
                        id="monthly-hours-{{ $id }}-{{ $month }}"
                        class="form-item"
                        wire:model="svcrole.monthly_hours.{{ $month }}">
                </div>
            @endforeach
        </div>
        @error('svcrole.monthly_hours.*') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="requires_update">
        <label class="switch">
            <input type="checkbox" wire:model="svcrole.updateMe" disabled>
            <span class="slider round"></span>
        </label>
    </td>
    <td class="svcr-list-item-cell" data-column="actions">
        <button class="svcr-list-item-action" data-action="edit"
                wire:click="editServiceRole({{ $id }})"
                @if ($isSaved) disabled @endif>
            <span class="material-symbols-outlined">edit</span>
        </button>
        <button class="svcr-list-item-action" data-action="delete"
                wire:click="deleteServiceRole({{ $id }})"
                @if ($isSaved) disabled @endif>
            <span class="material-symbols-outlined">delete</span>
        </button>
    </td>
</tr>
