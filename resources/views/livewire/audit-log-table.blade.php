<table class="svcr-table audit-logs-table">
    <thead>
        <tr class="svcr-table-header svcr-list-header">
            <th class="svcr-table-header-item svcr-list-header-item" data-column="select">
                <input type="checkbox" calss="form" wire:model="selectAll" />
            </th>
            <th class="svcr-table-header-item svcr-list-header-item" data-column="id">ID</th>
            <th class="svcr-table-header-item svcr-list-header-item" data-column="user">User</th>
            <th class="svcr-table-header-item svcr-list-header-item" data-column="action">Action</th>
            <th class="svcr-table-header-item svcr-list-header-item" data-column="description">Description</th>
            <th class="svcr-table-header-item svcr-list-header-item" data-column="schema">Schema</th>
            <th class="svcr-table-header-item svcr-list-header-item" data-column="operation">Operation</th>
            <th class="svcr-table-header-item svcr-list-header-item" data-column="old_val">Old</th>
            <th class="svcr-table-header-item svcr-list-header-item" data-column="new_val">New</th>
            <th class="svcr-table-header-item svcr-list-header-item" data-column="created">Created</th>
            <th class="svcr-table-header-item svcr-list-header-item" data-column="updated">Updated</th>
            <th class="text-center svcr-table-header-item svcr-list-header-item" data-column="actions">Actions</th>
        </tr>
    </thead>
    <tbody>
        @php

        // $this->auditLogs = AuditLog::orderBy('timestamp', 'desc')->paginate($this->perpage, ['*'], 'page', $this->page);
            $auditLogs = App\Models\AuditLog::orderBy('timestamp', 'desc')->paginate($this->perpage, ['*'], 'page', $this->page);
        @endphp
        @forelse ($auditLogs as $auditLog)
            <livewire:templates.audit-log-table-item :auditLog="$auditLog" :key="$auditLog->id" />
        @empty
            <tr class="svcr-list-item">
                <td class="svcr-list-item-cell" colspan="12">No audit logs found.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="svcr-table-footer">
            <td class="svcr-table-footer-item" colspan="12">
                {{ $auditLogs->links() }}
            </td>
        </tr>
    </tfoot>
</table>
