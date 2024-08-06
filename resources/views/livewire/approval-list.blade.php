<div class="w-full sreq-list" x-data="{
    search: @entangle('query'),
    filters: @entangle('filters'),
    selectedSort: @entangle('selectedSort'),
    selectedSortOrder: @entangle('selectedSortOrder'),
    selectedFilter: @entangle('selectedFilter'),
    showApprovalModal: @entangle('showApprovalModal'),
    role: @entangle('role'),
    dept: @entangle('dept'),
    selectedId: @entangle('selectedId'),
    clearFilters() {
        this.selectedFilter = {};
        for (const category in this.filters) {
            this.selectedFilter[category] = [];
        }
        this.search = '';
        @this.call('clear-filters');
    },
    sortColumn(column) {
        if (this.selectedSort === column) {
            this.selectedSortOrder = this.selectedSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            this.selectedSort = column;
            this.selectedSortOrder = 'asc';
        }
        @this.call('sortColumn', column);
    },
    aptype: @entangle('type').defer,
}">
    <h1 class="my-2 font-bold context-title" style="font-size: revert">
        <span class="content-title-text">{{ ucfirst($type) }} Approvals</span>
    </h1>
    <section class="w-full toolbar" id="toolbar">
        <section class="left toolbar-section">
            <div class="flex-grow toolbar-search-container">
                <span class="material-symbols-outlined icon toolbar-search-icon">
                    search
                </span>
                <input id="toolbar-search"
                    class="flex-grow toolbar-search"
                    type="search" placeholder="Search..."
                    x-on:input="search = $event.target.value; @this.dispatch('change-search-query', [search])"
                    />
                <span class="material-symbols-outlined icon toolbar-clear-search"
                    x-on:click="search = ''; @this.call('changeSearchQuery', '');">
                    clear
                </span>
            </div>
        </section>
        <section class="right toolbar-section">
            <div class="filter">
                <div class="filter-title filter-btn nos">
                    <span class="filter-title-text">
                        Filters
                    </span>
                    <span
                        class="material-symbols-outlined icon filter-title-icon">
                        filter_alt
                    </span>
                </div>
                <div class="filter-items-holder glass">
                    <button class="filter-clear-btn"
                        x-on:click="@this.dispatch('clear-filters')"
                        x-show="selectedFilter?.length > 0 && Object.keys(selectedFilter).some(category => selectedFilter[category]?.length > 0)"
                        x-cloak>
                        <span class="btn-title">
                            Clear Filters
                        </span>
                        <span class="material-symbols-outlined icon filter-clear">
                            clear
                        </span>
                    </button>
                    @foreach ($filters as $category => $filter)
                        <div class="filter-category" wire:key="filter_{{ $category }}">
                            <span class="filter-category-title">
                                {{ $category }}
                            </span>
                            {{-- @php
                                dd($filters, $category);
                            @endphp --}}
                            <div class="filter-items">
                                @foreach ($filter as $item)
                                    <div class="filter-item nos"
                                        wire:key="filter_{{ $category }}_{{ $item }}"
                                        x-data="{
                                            category: '{{ $category }}',
                                            item: '{{ $item }}',
                                            isChecked: false,
                                            toggleCheck() {
                                                this.isChecked = !this.isChecked;
                                                if (this.isChecked) {
                                                    selectedFilter[this.category].push(this.item);
                                                } else {
                                                    selectedFilter[this.category] = selectedFilter[this.category].filter(i => i !== this.item);
                                                }
                                                @this.dispatch('change-filter', [this.category, this.item, this.isChecked]);
                                            }
                                        }"
                                        x-init="() => {
                                            isChecked = selectedFilter && selectedFilter['{{ $category }}']
                                                ? selectedFilter['{{ $category }}'].includes('{{ $item }}')
                                                : false;
                                        }"
                                        x-on:click.stop="toggleCheck()"
                                        >
                                        <input type="checkbox"
                                            id="{{ $category }}-{{ $item }}"
                                            class="rounded-sm"
                                            x-on:click.stop
                                            x-on:change.stop
                                            x-bind:checked="isChecked"
                                            disabled
                                        />
                                        <span class="filter-item-text" data-text="{{$item}}">{{$item}}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </section>
    <section class="w-full">
        <table class="w-full svcr-table">
            <livewire:approval-list-header :headers="$headers" :type="$type" :key="'approval_list_header_{{ $type }}'" />
            <tbody>
                @php
                    $options = [
                        'delete' => true,
                        'approve' => true,
                        'reject' => true,
                        'cancel' => true,
                        // 'edit' => true,
                    ];
                @endphp
                @forelse ($approvals as $approval)
                    <livewire:templates.approval-list-item :approval="$approval" :type="$type" :key="'approval_list_item_'.$approval->id" :headers="$headers" :options="$options" />
                @empty
                    <tr class="svcr-list-item">
                        <td class="svcr-list-item-cell" colspan="100%">No approvals found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="svcr-table-footer">
                    <td class="svcr-table-footer-item" colspan="100%">
                        @if (!empty($approvals))
                            {{ $approvals->links() }}
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </section>

    {{-- dialog modal to set create a new user role for the user. Dropdown for existing user roles and a textbox for selected user. This is when approval is being approved and the type is registration --}}
    <x-dialog-modal wire:model="showApprovalModal" x-show="showApprovalModal" x-cloak>
        @php
            $viewing = \App\Models\Approval::find($selectedId);
            $selectedUser = null;
            if ($viewing) {
                $selectedUser = $viewing->user;
            }
        @endphp
        <x-slot name="title">
            {{ __('Viewing Approval: ') . $selectedId }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col w-full gap-2">
                <div class="flex flex-col w-full mt-4">
                    <label for="user" class="text-sm font-semibold text-gray-600">User</label>
                    <input type="text" name="user" id="user"
                    value="{{ $selectedUser ? $selectedUser->getName() : '' }}"
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" disabled />
                </div>
                <div class="flex flex-col w-full">
                    <label for="role" class="text-sm font-semibold text-gray-600">Select Role</label>
                    <select name="role" id="role" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" wire:model.live="role" required>
                        <option value="">Select Role</option>
                        @php
                            // $roles = \App\Models\UserRole::all();
                            // distinct role
                            $roles = [
                                'instructor' => 'Instructor',
                                'dept_staff' => 'Deptartment Staff',
                                'dept_head' => 'Deptartment Head',
                                'admin' => 'Administrator',
                            ];
                        @endphp
                        @foreach ($roles as $value => $role)
                            <option value="{{ $value }}">{{ $role }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- if role is dept_staff or dept_head, show depertment selector --}}
                <div class="flex flex-col w-full" x-show="role === 'dept_staff' || role === 'dept_head'" x-cloak>
                    <label for="department" class="text-sm font-semibold text-gray-600">Select Department</label>
                    <select name="department" id="department" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" wire:model.live="dept" required>
                        <option value="">Select Department</option>
                        @php
                            $departments = \App\Models\Department::all();
                        @endphp
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-button class="ml-2" wire:click="action('approve')" wire:loading.attr="disabled">
                {{ __('Approve') }}
            </x-button>
            <x-button class="ml-2" wire:click="action('reject')" wire:loading.attr="disabled">
                {{ __('Reject') }}
            </x-button>
            <x-button class="ml-2" wire:click="action('cancel')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-button>
            <x-secondary-button wire:click="closeApprovalModal" wire:loading.attr="disabled" class="ml-2">
                {{ __('Close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>
