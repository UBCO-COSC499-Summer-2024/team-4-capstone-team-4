<div x-data="{
    selectedSort: @entangle('selectedSort').defer,
    selectedSortOrder: @entangle('selectedSortOrder').defer,
    sortColumn(column) {
        if (this.selectedSort === column) {
            this.selectedSortOrder = this.selectedSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            this.selectedSort = column;
            this.selectedSortOrder = 'desc';
        }
        $wire.sortColumn(column);
    }
}">
    <table class="svcr-table audit-logs-table">
        <thead>
            <tr class="svcr-table-header svcr-list-header">
                <th class="svcr-table-header-item svcr-list-header-item" data-column="select">
                    <input type="checkbox" class="m-auto audit-item-checkall" wire:model="selectAll" />
                </th>
                @foreach (['id' => 'ID', 'user_id' => 'User', 'action' => 'Action', 'description' => 'Description', 'table_name' => 'Schema', 'operation_type' => 'Operation', 'old_val' => 'Old', 'new_val' => 'New', 'created_at' => 'Created', 'updated_at' => 'Updated'] as $column => $label)
                    <th class="svcr-table-header-item svcr-list-header-item" data-column="{{ $column }}">
                        <div class="svcr-table-th">
                            <span class="svcr-table-th-text">{{ $label }}</span>
                            <span class="audit-table-sort">
                                <span @click="sortColumn('{{ $column }}')" class="material-symbols-outlined icon audit-sort-icon">
                                    @if ($selectedSort === $column)
                                        @if ($selectedSortOrder === 'asc')
                                            arrow_drop_up
                                        @else
                                            arrow_drop_down
                                        @endif
                                    @else
                                        unfold_more
                                    @endif
                                </span>
                            </span>
                        </div>
                    </th>
                @endforeach
                <th class="text-center w-fit svcr-table-header-item svcr-list-header-item" data-column="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
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
    <script>
        // checkall
        ['DOMContentLoaded', 'livewire:load', 'livewire:update', 'livewire:init'].forEach(event => {
            document.addEventListener(event, function() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"].audit-item-check');
                const checkall = document.querySelector('input[type="checkbox"].audit-item-checkall');
                checkall.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = checkall.checked;
                    });
                });
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        checkall.checked = [...checkboxes].every(checkbox => checkbox.checked);
                    });
                });
            });
        });
    </script>
</div>
