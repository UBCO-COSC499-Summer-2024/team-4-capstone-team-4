<tr class="audit-log-table-item svcr-list-item">
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="select">
        <input type="checkbox" class="audit-item-check" wire:model="selected" value="{{ $auditLog->id }}" />
    </td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="id">{{ $auditLog->id }}</td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="user">{{ $auditLog->user_id }} - {{ $auditLog->user_alt }}</td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="action">
        <div class="w-fit m-auto !text-center">
            {{ $auditLog->action }}
        </div>
    </td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="description">{{ $auditLog->description }}</td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="schema">
        <div class="w-fit m-auto !text-center">
            {{ $auditLog->table_name }}
        </div>
    </td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="operation">
        <div class="w-fit m-auto !text-center">
            {{ $auditLog->operation_type }}
        </div>
    </td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="old_val">
        <div class="w-fit m-auto !text-center">
            <button @click="@this.dispatch('preview-data', ['{{$auditLog->id}}', '{{$auditLog->old_value}}'])">
                <span class="material-symbols-outlined">
                    file_present
                </span>
            </button>
        </div>
    </td>
    <td class="svcr-list-item-cell audit-log-table-item-cell m-auto !text-center" data-column="new_val">
        <div class="w-fit m-auto !text-center">
            <button @click="@this.dispatch('preview-data', ['{{$auditLog->id}}', '{{$auditLog->new_value}}'])">
                <span class="material-symbols-outlined">
                    file_present
                </span>
            </button>
        </div>
    </td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="created">{{ $auditLog->created_at }}</td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="updated">{{ $auditLog->updated_at }}</td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="id">
        <div class="justify-center m-auto svcr-list-item-actions w-fit">
            {{-- revert --}}
            <button wire:click="revert({{ $auditLog->id }})">
                <span class="material-symbols-outlined icon">
                    settings_backup_restore
                </span>
                {{-- <span class="btn-title">
                    Revert
                </span> --}}
            </button>
            <button wire:click="delete({{ $auditLog->id }})">
                <span class="material-symbols-outlined icon">
                    Delete
                </span>
                {{-- <span class="btn-title">
                    Delete
                </span> --}}
            </button>
        </div>
    </td>
</tr>
