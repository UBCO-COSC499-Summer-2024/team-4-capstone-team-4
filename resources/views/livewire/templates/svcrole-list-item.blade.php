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
            // dd($selectedValue);
        @endphp
        {{-- <livewire:dropdown-element
            title="Area"
            id="areaDropdownList-{{ $serviceRole->id }}"
            pre-icon="category"
            name="serviceRole.area_id"
            :values="$formattedAreas"
            value="
            {{ json_encode($selectedValue) }}
            "
            x-show="isEditing"
            x-cloak
            :key="'svcrArea-'.$serviceRole->id" /> --}}
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
