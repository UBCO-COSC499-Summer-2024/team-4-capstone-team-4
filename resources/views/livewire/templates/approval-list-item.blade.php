<tr class="svcr-list-item"
    x-data="{
    isEditing: @entangle('isEditing'),
    selected: @entangle('selected')
}">
    <td class="svcr-list-item-cell" data-column="select">
        <input type="checkbox" class="m-auto svcr-item-check" wire:model="selected" value="{{ $approval->id }}" />
    </td>
    @foreach ($headers as $index => $header)
        @php
            $column = $header['name'];
            $value = $approval[$column];
            // dd($header, $value);
        @endphp
        <td class="svcr-list-item-cell" data-column="{{ $column }}">
            <div class="svcr-list-td">
                <span class="svcr-list-td-text"
                    @if ($column !== 'id')
                        x-show="!isEditing"
                        x-cloak
                    @endif>{{ $value }}</span>
                @if(in_array($type, ['status', 'history', 'type']) && $column !== 'id')
                    <input type="text" class="svcr-list-td-input" x-show="isEditing" x-cloak
                        wire:model="approval.{{ $column }}" value="{{ $value }}" x-text="'{{$value}}'" />
                @endif
            </div>
        </td>
    @endforeach
    <td class="svcr-list-item-cell" data-column="actions">
        <div class="inline-flex justify-center w-full svcr-list-item-actions">
            @if ($options['edit'])
                {{-- cancel --}}
                <button class="svcr-list-item-action" wire:click="$call('cancelEdit', {{$approval->id}})" x-show="isEditing"
                    x-cloak>
                    <span class="material-symbols-outlined icon svcr-list-item-action-icon text-[#ea4040]">
                        cancel
                    </span>
                </button>
                <button class="svcr-list-item-action" wire:click="$call('editApproval', {{ $approval->id }});"
                    x-show="!isEditing" x-cloak>
                    <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                        edit
                    </span>
                </button>
                <button class="svcr-list-item-action" wire:click="$call('saveApproval', {{ $approval->id }});"
                    x-show="isEditing" x-cloak>
                    <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                        save
                    </span>
                </button>
            @elseif($options['delete'])
                <button class="svcr-list-item-action" wire:click="$call('deleteApproval', {{ $approval->id }})">
                    <span class="material-symbols-outlined icon svcr-list-item-action-icon text-[#ea4040]">
                        delete
                    </span>
                </button>
            @elseif($options['approve'])
                <button class="svcr-list-item-action" wire:click="$call('approveApproval', {{ $approval->id }})">
                    <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                        check
                    </span>
                </button>
            @elseif($options['reject'])
                <button class="svcr-list-item-action" wire:click="$call('rejectApproval', {{ $approval->id }})">
                    <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                        close
                    </span>
                </button>
            @elseif($options['cancel'])
                <button class="svcr-list-item-action" wire:click="$call('cancelApproval', {{ $approval->id }})">
                    <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                        close
                    </span>
                </button>
            @endif
        </div>
    </td>
</tr>
