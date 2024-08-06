<tr class="svcr-list-item"
    x-data="{
        isEditing: @entangle('isEditing'),
        selected: @entangle('selected'),
        isChecked: @entangle('selected'),
    }"
">
    <td class="svcr-list-item-cell" data-column="select">
        <input type="checkbox" class="m-auto svcr-item-check" wire:model="selected" value="{{ $approval->id }}" />
    </td>
    @php
        $dateCols = ['created_at', 'updated_at', 'approved_at', 'rejected_at', 'cancelled_at', 'completed_at', 'started_at', 'ended_at', 'due_at', 'submitted_at', 'approved_at', 'rejected_at', 'cancelled_at', 'requested_at'];
    @endphp
    @foreach ($headers as $index => $header)
        @php
            $column = $header['name'];
            $value = $approval[$column];
            $isDate = in_array($column, $dateCols) || strpos($column, '_at') !== false;
            $isRef = strpos($column, '_id') !== false;
            // if column type is date or contains _at
            if ($isDate) {
                if ($value !== null) {
                    if (is_string($value)) {
                        $value = \Carbon\Carbon::parse($value);
                    }
                    $value = $value->format('M d, Y H:i:s');
                }
            }
        @endphp
        <td class="svcr-list-item-cell" data-column="{{ $column }}">
            <div class="svcr-list-td">
                <span class="svcr-list-td-text"
                    @if ($column !== 'id')
                        x-show="!isEditing || '{{$isDate}}' || '{{$isRef}}'"
                        x-cloak
                    @endif>{{ $value }}</span>
                @if(in_array($type, ['status', 'history', 'type']) && $column !== 'id' && !$isDate && !$isRef)
                    <input type="text" class="svcr-list-td-input" x-show="isEditing" x-cloak
                        name="approval.{{ $column }}"
                        id="approval.{{ $column }}"
                        wire:model.live="approval.{{ $column }}" value="{{ $value }}" />
                @endif
            </div>
        </td>
    @endforeach
    <td class="svcr-list-item-cell" data-column="actions">
        <div class="inline-flex justify-center w-full svcr-list-item-actions">
            {{-- Check if edit option is set and true --}}
            @if (isset($options['edit']) && $options['edit'])
                {{-- Cancel --}}
                <button class="svcr-list-item-action" wire:click="$call('cancelEdit', {{ $approval->id }})" x-show="isEditing" x-cloak data-tippy-content="Cancel Edit">
                    <span class="material-symbols-outlined icon svcr-list-item-action-icon text-[#ea4040]">
                        cancel
                    </span>
                </button>
                {{-- Edit --}}
                <button class="svcr-list-item-action" wire:click="$call('editApproval', {{ $approval->id }});" x-show="!isEditing" x-cloak data-tippy-content="Edit">
                    <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                        edit
                    </span>
                </button>
                {{-- Save --}}
                <button class="svcr-list-item-action" wire:click="$call('saveApproval', {{ $approval->id }});" x-show="isEditing" x-cloak data-tippy-content="Save">
                    <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                        save
                    </span>
                </button>
            @endif

            @if (!in_array($type, ['status', 'history', 'type']))
                {{-- Check if approve option is set and true --}}
                @if (isset($options['approve']) && $options['approve'] && !$approval->isApproved() && !$approval->isCancelled() && !$approval->isRejected())
                    <button class="svcr-list-item-action"
                        @if ($approval->approvalType->name !== 'registration')
                            wire:click="$call('approveApproval', {{ $approval->id }})"
                        @else
                            wire:click="$dispatch('trigger-modal', [{{ $approval->id }}])"
                        @endif
                        data-tippy-content="Approve Request">
                        <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                            check
                        </span>
                    </button>
                @endif

                {{-- Check if reject option is set and true --}}
                @if (isset($options['reject']) && $options['reject']  && !$approval->isRejected() && !$approval->isCancelled() && !$approval->isApproved())
                    <button class="svcr-list-item-action"
                        @if ($approval->approvalType->name !== 'registration')
                            wire:click="$call('rejectApproval', {{ $approval->id }})"
                        @else
                            wire:click="$dispatch('trigger-modal', [{{ $approval->id }}])"
                        @endif
                        {{-- wire:click="$dispatch('trigger-modal', [{{ $approval->id }}])" --}}
                        data-tippy-content="Reject Request">
                        <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                            block
                        </span>
                    </button>
                @endif

                {{-- Check if cancel option is set and true --}}
                @if (isset($options['cancel']) && $options['cancel'] && !$approval->isCancelled() && !$approval->isRejected() && !$approval->isApproved())
                    <button class="svcr-list-item-action"
                        @if ($approval->approvalType->name !== 'registration')
                            wire:click="$call('cancelApproval', {{ $approval->id }})"
                        @else
                            wire:click="$dispatch('trigger-modal', [{{ $approval->id }}])"
                        @endif
                        {{-- wire:click="$dispatch('trigger-modal', [{{ $approval->id }}])" --}}
                        data-tippy-content="Cancel Request">
                        <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                            clear
                        </span>
                    </button>
                @endif
            @endif

            {{-- Check if delete option is set and true --}}
            @if (isset($options['delete']) && $options['delete'])
                <button class="svcr-list-item-action" wire:click="$call('deleteApproval', {{ $approval->id }})" data-tippy-content="Delete Request">
                    <span class="material-symbols-outlined icon svcr-list-item-action-icon text-[#ea4040]">
                        delete
                    </span>
                </button>
            @endif
        </div>
    </td>
</tr>
