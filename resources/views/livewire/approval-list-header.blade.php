<thead>
    <tr class="svcr-list-header">
        <th class="svcr-list-header-item" data-column="select">
            <input type="checkbox" class="m-auto svcr-item-checkall" wire:model="selectAll" />
        </th>
        @foreach ($headers as $index => $header)
            @php
                $column = $header['name'];
                $label = $header['label'];
            @endphp
            <th class="svcr-list-header-item" data-column="{{ $column }}">
                <div class="svcr-list-th">
                    <span class="svcr-list-th-text">{{ $label }}</span>
                    <span class="audit-list-sort">
                        <span wire:click="$dispatch('sort-selected', ['{{ $column }}'])" class="material-symbols-outlined icon audit-sort-icon">
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
        <th class="text-center w-fit svcr-list-header-item" data-column="actions">Actions</th>
    </tr>
</thead>
