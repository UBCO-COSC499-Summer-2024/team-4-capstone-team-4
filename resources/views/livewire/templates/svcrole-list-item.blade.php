@php
    $user = Auth::user();
@endphp
<tr class="svcr-list-item"
    id="svcrli-{{ $serviceRole->id }}"
    x-data="{ isEditing: @entangle('isEditing') }"
    {{-- :class="{ 'bg-default': !isEditing, 'bg-editing': isEditing }"> --}}
    :class="{ 'bg-default': !isEditing, 'bg-editing': isEditing }"
    {{-- wire:key="svcrli-{{ $serviceRole->id }}" --}}
    >
    @if(!$user->hasOnlyRole('instructor'))
        <td class="svcr-list-item-cell w-fit" data-column="select">
            <input type="checkbox" class="svcr-list-item-select"
                id="svcr-select-{{ $serviceRole->id }}"
                value="{{ $serviceRole->id }}"
                wire:model.live="isSelected"
                wire:change="$dispatch('select-service-role', [{{ $serviceRole->id }}])"
            >
        </td>
    @endif

    <td class="svcr-list-item-cell" data-column="id">
        <span class="svcr-list-item-title" x-cloak>
            {{ $serviceRole->id }}
        </span>
    </td>

    <td class="svcr-list-item-cell" data-column="name">
        <span x-show="!isEditing" class="svcr-list-item-title" x-cloak>
            <x-link
                href="{{ route('svcroles.manage.id', $serviceRole->id) }}"
                title="{{ $serviceRole->name }}"
                class="hover:underline "
            />
        </span>
        <input x-show="isEditing" type="text" class="svcr-list-item-edit"
               wire:model="srname"
               x-cloak value="{{ $srname }}">
    </td>

    <td class="svcr-list-item-cell" data-column="area">
        <span x-show="!isEditing" class="svcr-list-item-title" x-cloak>
            @switch($serviceRole->area->name)
                @case("Computer Science")
                    <span class="font-bold" style="color: rgba(29, 154, 202, 1);">{{ __("COSC") }}</span>
                    @break
                @case("Mathematics")
                    <span class="font-bold" style="color: rgba(44, 160, 44, 1);">{{ __("MATH") }}</span>
                    @break
                @case("Physics")
                    <span class="font-bold" style="color: rgba(249, 168, 37, 1);">{{ __("PHYS") }}</span>
                    @break
                @case("Statistics")
                    <span class="font-bold" style="color: rgba(214, 39, 40, 1);">{{ __("STAT") }}</span>
                    @break
                @default

            @endswitch
        </span>
        @php
            $selectedValue = [$serviceRole->area_id => $serviceRole->area->name];
        @endphp
        <select x-show="isEditing" class="svcr-list-item-edit"
                wire:model="srarea_id"
                x-cloak>
            @foreach ($areas as $area)
                <option value="{{ $area->area_id }}"
                        @if ($srarea_id == $area->area_id) selected @endif
                    >{{ $area->name }}</option>
            @endforeach
        </select>
    </td>

    <td class="svcr-list-item-cell" data-column="year">
        <span x-show="!isEditing" class="svcr-list-item-title" x-cloak>
            {{ $serviceRole->year }}
        </span>
        <input x-show="isEditing" type="number" class="svcr-list-item-edit"
               wire:model="sryear"
               x-cloak value="{{ $sryear }}">
    </td>

    <td class="svcr-list-item-cell" data-column="description">
        <span x-show="!isEditing" class="svcr-list-item-title" x-cloak>
            {{ $serviceRole->description }}
        </span>
        <input x-show="isEditing" type="text" class="svcr-list-item-edit"
               wire:model="srdescription"
               x-cloak value="{{ $srdescription }}">
    </td>

    <td class="svcr-list-item-cell" data-column="room">
        <span class="svcr-list-item-title" x-cloak x-show="!isEditing">
            {{ $serviceRole->room ? $serviceRole->room : 'Not Assigned' }}
        </span>
        <div x-show="isEditing" x-cloak class="flex items-center justify-start gap-2">
            {{-- roomB / roomN / roomS input in an inline flex --}}
            <input type="text" class="svcr-list-item-edit !min-w-8 !max-w-20"
                wire:model="srroom"
                hidden
                value="{{ $srroom }}"
                x-cloak>
            <input type="text" class="svcr-list-item-edit !min-w-12 !max-w-16"
                wire:model="roomB"
                placeholder="LIB"
                value="{{ $roomB }}"
                x-cloak>
            <input type="text" class="svcr-list-item-edit !min-w-12 !max-w-16"
                wire:model="roomN"
                placeholder="123"
                value="{{ $roomN }}"
                x-cloak>
            <input type="text" class="svcr-list-item-edit !min-w-4 !max-w-8"
                wire:model="roomS"
                placeholder="B"
                value="{{ $roomS }}"
                x-cloak>
        </div>
    </td>

    <td class="svcr-list-item-cell" data-column="instructors">
        <span class="flex items-center justify-start svcr-list-item-title" x-cloak>
            @forelse ($serviceRole->instructors->take(1) as $instructor)
                {{ $instructor->getName() }}@if (!$loop->last), @endif
            @empty
                <span class="text-gray-400">No instructors</span>
            @endforelse
            @if ($serviceRole->instructors->count() > 1)
                <span class="gap-4 text-blue-500 cursor-pointer hover:text-blue-700 material-symbols-outlined icon" onclick="window.location='{{ route('svcroles.manage.id', $serviceRole->id) }}'"
                    data-tippy-content="View more instructors"
                >more</span>
            @endif
        </span>
    </td>

    {{-- extra hours --}}

    @if(!$user->hasOnlyRole('instructor'))
        <td class="svcr-list-item-cell" data-column="manage">
            <div class="flex justify-end item-end svcr-list-item-actions">
                <button class="svcr-list-item-action"
                        title="Cancel"
                        @click="isEditing = !isEditing"
                        x-show="isEditing"
                        x-cloak>
                    <span class="material-symbols-outlined icon icon-cancel-edit">block</span>
                </button>

                @if(!$serviceRole->archived)
                    <button class="svcr-list-item-action"
                            :title="'Edit'"
                            @click="isEditing = !isEditing"
                            {{-- wire:click="editServiceRole({{ $serviceRole->id }})" --}}x-show="!isEditing"
                            x-cloak>
                        <span class="material-symbols-outlined icon text-[#3b4779]" x-text="'edit'"></span>
                    </button>
                @endif

                {{-- save button --}}
                @if(!$serviceRole->archived)
                <button class="svcr-list-item-action"
                        title="Save"
                        x-show="isEditing"
                        x-cloak
                        x-on:click="
                        $dispatch('update-service-role', {'id': {{ $serviceRole->id }} })">
                    <span class="material-symbols-outlined icon text-[#3b4779]">save</span>
                </button>
                @endif

                <button class="svcr-list-item-action"
                        wire:click="confirmSArchive({{ $serviceRole->id }})">
                    <span
                        class="material-symbols-outlined icon text-[#ea3030]"
                    {{
                        $serviceRole->archived ? 'data-tippy-content=Unarchive' : 'data-tippy-content=Archive'
                    }}
                        {{-- title="{{
                            $serviceRole->archived ? 'Unarchive' : 'Archive'
                        }}" --}}
                    >
                        @if (!$serviceRole->archived)
                            archive
                        @else
                            unarchive
                        @endif
                    </span>
                </button>

                @if(auth()->user()->hasRoles(['admin']))
                    <button class="svcr-list-item-action"
                            wire:click="confirmSDelete({{ $serviceRole->id }})">
                        <span class="material-symbols-outlined icon text-[#ea3030]">delete</span>
                    </button>
                @endif
            </div>
        </td>
    @endif
</tr>
