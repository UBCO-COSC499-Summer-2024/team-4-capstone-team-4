<tr class="extra-hour-view-item"
    id="ehvi-{{ $extraHour->id }}"
    x-data="{ isEditing: false }"
    :class="{ 'bg-default': !isEditing, 'bg-editing': isEditing }">

    <td class="ehvi-cell" data-column="name">
        <span x-show="!isEditing" class="ehvi-title" x-cloak>
            {{ $extraHour->name }}
        </span>
        <input x-show="isEditing" type="text" class="ehvi-edit"
               wire:model.defer="extraHour.name"
               x-cloak>
    </td>

    <td class="ehvi-cell" data-column="description">
        <span x-show="!isEditing" class="ehvi-title" x-cloak>
            {{ $extraHour->description }}
        </span>
        <input x-show="isEditing" type="text" class="ehvi-edit"
               wire:model.defer="extraHour.description"
               x-cloak>
    </td>

    <td class="ehvi-cell" data-column="hours">
        <span x-show="!isEditing" class="ehvi-title" x-cloak>
            {{ $extraHour->hours }}
        </span>
        <input x-show="isEditing" type="number" class="ehvi-edit"
               wire:model.defer="extraHour.hours"
               x-cloak>
    </td>

    <td class="ehvi-cell" data-column="year">
        <span x-show="!isEditing" class="ehvi-title" x-cloak>
            {{ $extraHour->year }}
        </span>
        <input x-show="isEditing" type="number" class="ehvi-edit"
               wire:model.defer="extraHour.year"
               x-cloak>
    </td>

    <td class="ehvi-cell" data-column="month">
        <span x-show="!isEditing" class="ehvi-title" x-cloak>
            {{ $extraHour->month }}
        </span>
        <input x-show="isEditing" type="number" class="ehvi-edit"
               wire:model.defer="extraHour.month"
               x-cloak>
    </td>

    <td class="ehvi-cell" data-column="assigner">
        <span x-show="!isEditing" class="ehvi-title" x-cloak>
            {{ $extraHour->assigner->user->firstname }} {{ $extraHour->assigner->user->lastname }}
        </span>
        <select x-show="isEditing" class="ehvi-edit"
                wire:model.defer="extraHour.assigner_id"
                x-cloak>
            <option value="">Select Assigner</option>
            @foreach($user_roles as $role)
                <option value="{{ $role->id }}">{{ $role->user->name }}</option>
            @endforeach
        </select>
    </td>

    <td class="ehvi-cell" data-column="instructor">
        <span x-show="!isEditing" class="ehvi-title" x-cloak>
            {{ $extraHour->instructor->user->firstname }} {{ $extraHour->instructor->user->lastname }}
        </span>
        <select x-show="isEditing" class="ehvi-edit"
                wire:model.defer="extraHour.instructor_id"
                x-cloak>
            <option value="">Select Instructor</option>
            @foreach($user_roles as $role)
                <option value="{{ $role->id }}">{{ $role->user->name }}</option>
            @endforeach
        </select>
    </td>

    <td class="ehvi-cell" data-column="area">
        <span x-show="!isEditing" class="ehvi-title" x-cloak>
            {{ $extraHour->area->name }}
        </span>
        <select x-show="isEditing" class="ehvi-edit"
                wire:model.defer="extraHour.area_id"
                x-cloak>
            <option value="">Select Area</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
    </td>

    <td class="ehvi-cell" data-column="manage">
        <div class="ehvi-actions">
            <button class="ehvi-action"
                    title="Cancel"
                    @click="isEditing = false"
                    x-show="isEditing"
                    x-cloak>
                <span class="material-symbols-outlined icon icon-cancel-edit">block</span>
            </button>

            <button class="ehvi-action"
                    :title="isEditing ? 'Save' : 'Edit'"
                    @click="isEditing = !isEditing"
                    wire:click="edit({{ $extraHour->id }})">
                <span class="material-symbols-outlined icon" x-text="isEditing ? 'save' : 'edit'"></span>
            </button>

            <button class="ehvi-action"
                    wire:click="$emit('deleteExtraHour', {{ $extraHour->id }})">
                <span class="material-symbols-outlined icon">delete</span>
            </button>
        </div>
    </td>
</tr>
