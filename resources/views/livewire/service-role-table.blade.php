<table id="svcr-table" class="svcr-table">
    <thead>
        <tr class="svcr-list-header">
            <th class="svcr-list-header-item">
                ID
            </th>
            <th class="svcr-list-header-item">
                Name
            </th>
            <th class="svcr-list-header-item">
                Description
            </th>
            <th class="svcr-list-header-item">
                Room
            </th>
            <th class="svcr-list-header-item">
                Area
            </th>
            <th class="svcr-list-header-item">
                Year
            </th>
            <th class="svcr-list-header-item">
                Monthly Hours
            </th>
            <th class="svcr-list-header-item">
                <div class="flex flex-row items-center justify-start gap-2">
                    Archived
                    <span class="material-symbols-outlined icon" data-tippy-content="Toggle this switch to archive/unarchive the service role.">
                        help
                    </span>
                </div>
            </th>
            <th class="svcr-list-header-item">
                <div class="flex flex-row items-center justify-start gap-2">
                    Update
                    <span class="material-symbols-outlined icon" data-tippy-content="Activate this switch to update the service role. Otherwise, it will be ignored.">
                        help
                    </span>
                </div>
            </th>
            <th class="svcr-list-header-item">
                    <div class="flex items-center justify-center">
                        <span class="material-symbols-outlined icon">
                            settings
                        </span>
                    </div>
            </th>
        </tr>
    </thead>
    <tbody>
        @forelse($svcroles as $index => $svcrole)
            <livewire:templates.service-role-table-item :svcrole="$svcrole" :key="$svcrole['id']" :id="$svcrole['id']" />
        @empty
            <tr class="svcr-list-item empty">
                <td class="svcr-list-item-cell empty" colspan="9">
                    Click the "Add Row" button to add a new service role.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
