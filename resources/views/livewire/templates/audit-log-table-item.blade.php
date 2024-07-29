<tr class="audit-log-table-item svcr-list-item">
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="select">
        <input type="checkbox" class="audit-item-check" wire:model="selected" value="{{ $auditLog->id }}" />
    </td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="id">{{ $auditLog->id }}</td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="user">{{ $auditLog->user_id }} - {{ $auditLog->user_alt }}</td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="action">{{ $auditLog->action }}</td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="description">{{ $auditLog->description }}</td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="schema">{{ $auditLog->table_name }}</td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="operation">{{ $auditLog->operation_type }}</td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="old_val">
        <button onclick="document.getElementById('auditLogModal{{ $auditLog->id }}').style.display='block'">
            <span class="material-symbols-outlined">
                file_present
            </span>
        </button>
    </td>
    <td class="svcr-list-item-cell audit-log-table-item-cell" data-column="new_val">
        <button onclick="document.getElementById('auditLogModal{{ $auditLog->id }}').style.display='block'">
            <span class="material-symbols-outlined">
                file_present
            </span>
        </button>
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
