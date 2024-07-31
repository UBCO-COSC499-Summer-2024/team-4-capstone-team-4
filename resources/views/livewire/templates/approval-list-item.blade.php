<tr class="svcr-list-item">
    <td class="svcr-list-item-cell" data-column="select">
        <input type="checkbox" class="m-auto svcr-item-check" wire:model="selected" value="{{ $approval->id }}" />
    </td>
    <td class="svcr-list-item-cell" data-column="id">
        <span class="svcr-list-item-text">{{ $approval->id }}</span>
    </td>
    <td class="svcr-list-item-cell" data-column="name">
        <span class="svcr-list-item-text">{{ $approval->name }}</span>
    </td>
    <td class="svcr-list-item-cell" data-column="status">
        <span class="svcr-list-item-text">{{ $approval->status }}</span>
    </td>
    <td class="svcr-list-item-cell" data-column="details">
        <span class="svcr-list-item-text">{{ $approval->details }}</span>
    </td>
    <td class="svcr-list-item-cell" data-column="actions">
        <div class="svcr-list-item-actions">
            <button class="svcr-list-item-action" wire:click="editApproval({{ $approval->id }})">
                <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                    edit
                </span>
            </button>
            <button class="svcr-list-item-action" wire:click="deleteApproval({{ $approval->id }})">
                <span class="material-symbols-outlined icon svcr-list-item-action-icon">
                    delete
                </span>
            </button>
        </div>
    </td>
</tr>
