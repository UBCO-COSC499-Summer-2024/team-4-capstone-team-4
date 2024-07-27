<tr class="svcr-list-item" wire:key="{{ $id }}">
    <td class="svcr-list-item-cell" data-column="name">
        <input type="text" class="form-item" wire:model.defer="svcrole.name">
        @error('svcrole.name') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="description">
        <input type="text" class="form-item" wire:model.defer="svcrole.description">
        @error('svcrole.description') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="area">
        <select class="form-item" wire:model.defer="svcrole.area_id">
            <option value="">Select Area</option>
            @foreach ($areas as $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
        @error('svcrole.area_id') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="year">
        <input type="number" class="form-item" wire:model.defer="svcrole.year">
        @error('svcrole.year') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="svcr-list-item-cell" data-column="monthly_hours">
        @foreach ($svcrole->monthly_hours as $month => $hour)
            <div class="monthly-hours-input-group">
                <label for="monthly-hours-{{ $id }}-{{ $month }}">{{ Str::ucfirst($month) }}:</label>
                <input type="number" min="0"
                       id="monthly-hours-{{ $id }}-{{ $month }}"
                       class="form-item"
                       wire:model.defer="svcrole.monthly_hours.{{ $month }}">
            </div>
        @endforeach
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
