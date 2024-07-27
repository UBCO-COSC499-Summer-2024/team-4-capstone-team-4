<table id="svcr-table" class="svcr-table">
    <thead>
        <tr class="svcr-list-header">
            <th class="svcr-list-header-item">
                Name
            </th>
            <th class="svcr-list-header-item">
                Description
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
                Requires Update
            </th>
            <th class="svcr-list-header-item">
                Actions
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($svcroles as $svcrole)
            <livewire:templates.service-role-table-item :svcrole="$svcrole" :key="time()" />
        @endforeach
    </tbody>
</table>
