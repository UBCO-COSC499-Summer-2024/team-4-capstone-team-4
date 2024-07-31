<thead>
    <tr class="svcr-table-header">
        <th class="svcr-table-header-item" data-column="select">
            <input type="checkbox" class="m-auto svcr-item-checkall" wire:model="selectAll" />
        </th>
        @foreach ($headers as $header)
            @php
                $column = $header['name'];
                $label = $header['label'];
            @endphp
            <th class="svcr-table-header-item" data-column="{{ $column }}">
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
        <th class="text-center w-fit svcr-table-header-item" data-column="actions">Actions</th>
    </tr>
</thead>
