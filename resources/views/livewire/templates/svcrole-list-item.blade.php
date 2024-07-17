<tr class="svcr-list-item"
    id="svcrli-{{ $serviceRole->id }}"
    x-data="{ isEditing: @entangle('isEditing') }"
    {{-- :class="{ 'bg-default': !isEditing, 'bg-editing': isEditing }"> --}}
    :class="{ 'bg-default': !isEditing, 'bg-editing': isEditing }"
    {{-- wire:key="svcrli-{{ $serviceRole->id }}" --}}
    >

    <td class="svcr-list-item-cell" data-column="select">
        <input type="checkbox" class="svcr-list-item-select"
            id="svcr-select-{{ $serviceRole->id }}"
            value="{{ $serviceRole->id }}"
            {{-- wire:model.live="isSelected" --}}
        >
    </td>

    <td class="svcr-list-item-cell" data-column="name">
        <span x-show="!isEditing" class="svcr-list-item-title" x-cloak>
            <x-link href="{{ route('svcroles.manage.id', $serviceRole->id) }}" title="{{ $serviceRole->name }}" />
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
            @forelse ($serviceRole->instructors->take(1) as $instructor)
                {{ $instructor->getName() }}@if (!$loop->last), @endif
            @empty
                <span class="text-gray-400">No instructors</span>
            @endforelse
            @if ($serviceRole->instructors->count() > 2)
                <button class="text-blue-500 hover:text-blue-700" onclick="window.location='{{ route('svcroles.manage.id', $serviceRole->id) }}'">
                    <span>More</span>
                </button>
            @endif
        </span>
    </td>

    {{-- extra hours --}}
    <td class="svcr-list-item-cell" data-column="extra-hours">
        <div class="svcr-list-item-actions">
            <button class="px-2 rounded-md shadow-sm svcr-list-item-action bg-slate-100" id="svcr-extra-hours-add-{{ $serviceRole->id }}"
                    title="Add Extra Hours"
                    wire:click="
                    $dispatch('item-modal-id', { id: {{ $serviceRole->id }} });
                    $dispatch('open-modal', { component: 'extra-hour-form', arguments: {serviceRoleId: {{ $serviceRole->id }} }})"
                >
                <span class="material-symbols-outlined icon">more_time</span>
                <span>Add</span>
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
