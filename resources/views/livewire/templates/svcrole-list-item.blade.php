<tr class="svcr-list-item"
    id="svcrli-{{ $serviceRole->id }}"
    x-data="{ isEditing: false }"
    :class="{ 'bg-default': !isEditing, 'bg-editing': isEditing }">

    <td class="svcr-list-item-cell" data-column="select">
        <input type="checkbox" class="svcr-list-item-select"
            id="svcr-select-{{ $serviceRole->id }}"
            value="{{ $serviceRole->id }}"
            {{-- wire:model.live="isSelected" --}}
        >
    </td>

    <td class="svcr-list-item-cell" data-column="name">
        <span x-show="!isEditing" class="svcr-list-item-title" x-cloak>
            {{ $serviceRole->name }}
        </span>
        <input x-show="isEditing" type="text" class="svcr-list-item-edit"
               {{-- wire:model="serviceRole.name" --}}
               x-cloak value="{{ $serviceRole->name }}">
    </td>

    <td class="svcr-list-item-cell" data-column="area">
        <span x-show="!isEditing" class="svcr-list-item-title" x-cloak>
            {{ $serviceRole->area->name }}
        </span>
        @php
            $selectedValue = [$serviceRole->area_id => $serviceRole->area->name];
        @endphp
        <select x-show="isEditing" class="svcr-list-item-edit"
                {{-- wire:model="serviceRole.area_id" --}}
                x-cloak>
            @foreach ($areas as $area)
                <option value="{{ $area->area_id }}"
                        @if ($serviceRole->area_id == $area->area_id) selected @endif
                    >{{ $area->name }}</option>
            @endforeach
        </select>
    </td>

    <td class="svcr-list-item-cell" data-column="description">
        <span x-show="!isEditing" class="svcr-list-item-title" x-cloak>
            {{ $serviceRole->description }}
        </span>
        <input x-show="isEditing" type="text" class="svcr-list-item-edit"
               {{-- wire:model="serviceRole.description" --}}
               x-cloak value="{{ $serviceRole->description }}">
    </td>

    <td class="svcr-list-item-cell" data-column="instructors">
        <span x-show="!isEditing" class="svcr-list-item-title" x-cloak>
            {{ $serviceRole->instructors }} </span>
    </td>

    {{-- extra hours --}}
    <td class="svcr-list-item-cell" data-column="extra-hours">
        {{-- <span x-show="!isEditing" class="svcr-list-item-title" x-cloak>
            {{ $serviceRole->extra_hours }}
        </span> --}}
        <div class="svcr-list-item-actions">
            {{-- modal button to view/edit extra hours --}}
            <button class="svcr-list-item-action" id="svcr-extra-hours-add-{{ $serviceRole->id }}"
                    title="Add Extra Hours"
                    wire:click="openExtraHourForm({{ $serviceRole->id }})"
                    @click="showExtraHoursForm = true"
                    x-cloak>
                <span class="material-symbols-outlined icon">add</span>
            </button>
            <button class="svcr-list-item-action" id="svcr-extra-hours-view-{{ $serviceRole->id }}"
                    title="Manage Extra Hours"
                    wire:click="openExtraHourView({{ $serviceRole->id }})"
                    @click="showExtraHoursView = true"
                    x-cloak>
                <span class="material-symbols-outlined icon">visibility</span>
            </button>
        </button>
    </td>

    <td class="svcr-list-item-cell" data-column="manage">
        <div class="svcr-list-item-actions">
            <button class="svcr-list-item-action"
                    title="Cancel"
                    @click="isEditing = !isEditing"
                    x-show="isEditing"
                    x-cloak>
                <span class="material-symbols-outlined icon icon-cancel-edit">block</span>
            </button>

            <button class="svcr-list-item-action"
                    :title="isEditing ? 'Save' : 'Edit'"
                    @click="isEditing = !isEditing"
                    wire:click="editServiceRole({{ $serviceRole->id }})">
                <span class="material-symbols-outlined icon" x-text="isEditing ? 'save' : 'edit'"></span>
            </button>

            <button class="svcr-list-item-action"
                    wire:click="confirmDelete({{ $serviceRole->id }})">
                <span class="material-symbols-outlined icon">delete</span>
            </button>
        </div>
    </td>
</tr>
