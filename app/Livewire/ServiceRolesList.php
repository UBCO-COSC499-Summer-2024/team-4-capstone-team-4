<?php

namespace App\Livewire;

use App\Models\ServiceRole;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ServiceRolesList extends Component
{
    use WithPagination;
    public $links = [];
    // public $columns = [];
    public $viewMode = 'table';
    public $pageMode = 'pagination';
    public $searchQuery = '';
    public $searchCategory = ''; // column to search
    public $selectedFilter = '';
    public $filterValue = '';
    public $selectedSort = '';
    public $selectedSortOrder = 'asc';
    public $selectedGroup = '';
    public $pageSize = 10;
    public $selectedItems = [];
    public $showExtraHourForm = false;
    public $serviceRoleIdForModal; // To store the serviceRoleId
    protected $queryString = [
        'viewMode' => ['except' => 'table'],
        'pageMode' => ['except' => 'pagination'],
        'searchQuery' => ['except' => ''],
        'searchCategory' => ['except' => ''],
        'selectedFilter' => ['except' => ''],
        'filterValue' => ['except' => ''],
        'selectedSort' => ['except' => ''],
        'selectedSortOrder' => ['except' => 'asc'],
        'selectedGroup' => ['except' => ''],
        'pageSize' => ['except' => 20]
    ];

    protected $rules = [
        'viewMode' => 'required|in:card,list,table',
        'pageMode' => 'required|in:view,pagination,infinite',
        'searchQuery' => 'nullable|string',
        'searchCategory' => 'nullable|string',
        'selectedFilter' => 'nullable|string',
        'filterValue' => 'nullable|string',
        'selectedSort' => 'nullable|string',
        'selectedSortOrder' => 'nullable|string',
        'selectedGroup' => 'nullable|string',
        'pageSize' => 'required|integer|min:1',
    ];

    protected $listeners = [
        'changeViewMode' => 'changeViewMode',
        'changePageMode' => 'changePageMode',
        'changePageSize' => 'changePageSize',
        'changeSearchQuery' => 'changeSearchQuery',
        'changeSearchCategory' => 'changeSearchCategory',
        'changeFilter' => 'changeFilter',
        'changeSort' => 'changeSort',
        'changeGroup' => 'changeGroup',
        'clearFilters' => 'clearFilters',
        'clearSearch' => 'clearSearch',
        'clearFilter' => 'clearFilter',
        'clearSort' => 'clearSort',
        'clearGroup' => 'clearGroup',
        'clearAll' => 'clearAll',
        'resetFilters' => 'resetFilters',
        'open-modal' => 'openModal',
        'closeModal' => 'closeModal',
        'performAction' => 'performAction',
        'deleteAllSelected' => 'deleteSelected',
        'saveSelected' => 'saveSelected',
        'exportSelected' => 'exportSelected',
        'selectItem' => 'handleItemSelected',
        'toolbarUpdated' => 'handleToolbarUpdate',
        'applyActions' => 'handleApplyActions',
        'filtersReset' => 'resetFilters', // For resetting filters
        'update-modal-id' => 'updateModalId',
    ];

    public function mount($links = []) {
        $this->links = $links;
    }

    public function handleToolbarUpdate($data)
    {
        // Log or debug if needed
        // logger('Toolbar Updated:', $data);

        // Update component properties (if needed)
        // and re-fetch data based on the toolbar changes
        $this->resetPage(); // Reset pagination when filters change
    }

    public function resetPage() {
        $this->render();
    }

    public function handleApplyActions($selectedActions)
    {
        // // Log or debug if needed
        // logger('Applying Actions:', $selectedActions, 'to items:', $this->selectedItems);

        // Implement your logic to handle the selected actions here
        // For example:
        if (in_array('delete', $selectedActions)) {
            $this->deleteSelected();
        }

        if (in_array('export', $selectedActions)) {
            $this->exportSelected();
        }

        if (in_array('save', $selectedActions)) {
            $this->saveSelected();
        }

        if (in_array('edit', $selectedActions)) {
            // enable editable for selected items
            $this->toggleEdit();
        }
    }

    public function handleItemSelected($itemId, $checked) {
        if ($checked) {
            $this->selectedItems[] = $itemId;
        } else {
            $this->selectedItems = array_diff($this->selectedItems, [$itemId]);
        }
    }

    public function handleCheckAll($checked) {
        if ($checked) {
            $this->selectedItems = $this->allItemIds();
        } else {
            $this->selectedItems = [];
        }
    }

    private function allItemIds() {
        // Replace with your logic to fetch all item IDs
        // For example, if you have a collection of items
        return $this->items->pluck('id')->toArray();
    }

    public function getColumns($modelOrTable) {
        if (is_string($modelOrTable) && str_contains($modelOrTable, '.')) {
            [$table] = explode('.', $modelOrTable, 2);
            return DB::getSchemaBuilder()->getColumnListing($table);
        } elseif (is_object($modelOrTable) && $modelOrTable instanceof \Illuminate\Database\Eloquent\Model) {
            return $modelOrTable->getConnection()->getSchemaBuilder()->getColumnListing($modelOrTable->getTable());
        } elseif (is_string($modelOrTable) && class_exists($modelOrTable)) {
            $model = new $modelOrTable;
            return $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        } else {
            throw new \InvalidArgumentException('Invalid argument passed to getColumns. Must be a model instance, model class name, or table name.');
        }
    }

    public function refresh() {
        $this->render();
    }

    public function render() {
        $serviceRolesQuery = ServiceRole::query();

        // if area requested in filter/sort/category/group, use the area id for each item and do the action based on area name and or description and or id.

        if (!empty($this->searchQuery)) {
            $searchableColumns = $this->getColumns(ServiceRole::class);
            // dd($this->searchCategory, $searchableColumns, in_array($this->searchCategory, $searchableColumns));
            if (!empty($this->searchCategory) && in_array($this->searchCategory, $searchableColumns)) {
                // dd($this->searchCategory);
                // $serviceRolesQuery->where($this->searchCategory, 'like', '%' . $this->searchQuery . '%');
                if ($this->searchCategory === 'area_id') {
                    $serviceRolesQuery->whereHas('area', function ($query) {
                        $query->where('name', 'like', '%' . $this->searchQuery . '%');
                    });
                } else {
                    $serviceRolesQuery->where($this->searchCategory, 'like', '%' . $this->searchQuery . '%');
                }
            } else {
                $serviceRolesQuery->where(function ($query) use ($searchableColumns) {
                    foreach ($searchableColumns as $column) {
                        $query->orWhere($column, 'like', '%' . $this->searchQuery . '%');
                    }
                });
            }
        }

        // if (!empty($this->selectedFilter) && !empty($this->filterValue)) {
        //     $serviceRolesQuery->where($this->selectedFilter, $this->filterValue);
        // }
        if (!empty($this->selectedFilter) && !empty($this->filterValue)) {
            if ($this->selectedFilter === 'area' || $this->selectedFilter === 'area_id') {
                $serviceRolesQuery->whereHas('area', function ($query) {
                    $query->where('name', 'like', '%' . $this->filterValue . '%');
                });
            } else {
                // If filtering by other columns of ServiceRole
                $serviceRolesQuery->where($this->selectedFilter, $this->filterValue);
            }
        }

        // if (!empty($this->selectedSort)) {
        //     $serviceRolesQuery->orderBy($this->selectedSort, $this->selectedSortOrder);
        // }

        if (!empty($this->selectedSort)) {
            if (str_contains($this->selectedSort, '.')) {
                [$relation, $column] = explode('.', $this->selectedSort);

                // Handle sorting by 'area.name' specifically
                if ($relation === 'area' && $column === 'name') {
                    $serviceRolesQuery->join('areas', 'service_roles.area_id', '=', 'areas.id')
                                     ->orderBy('areas.name', $this->selectedSortOrder);
                } else {
                    // Handle other relations if needed
                    $serviceRolesQuery->orderBy(
                        ServiceRole::select($column)
                            ->from('service_roles')
                            ->whereColumn('service_roles.id', 'service_roles.' . $relation . '_id'),
                        $this->selectedSortOrder
                    );
                }

            } else {
                $serviceRolesQuery->orderBy($this->selectedSort, $this->selectedSortOrder);
            }
        }

        if (!empty($this->selectedGroup)) {
            $serviceRolesQuery->groupBy($this->selectedGroup, 'id');
        }

        $serviceRoles = $this->pageMode === 'pagination'
            ? $serviceRolesQuery->with('area')->paginate($this->pageSize)
            : $serviceRolesQuery->with('area')->get();

        return view('livewire.service-roles-list', [
            'serviceRoles' => $serviceRoles,
            'links' => $this->links,
            'viewMode' => $this->viewMode,
            'pageMode' => $this->pageMode,
        ]);
    }

    public function changeViewMode($mode) {
        $this->viewMode = $mode;
    }

    public function changePageMode($mode) {
        $this->pageMode = $mode;
    }

    public function changePageSize($size) {
        $this->pageSize = $size;
    }

    public function changeSearchQuery($query) {
        $this->searchQuery = $query;
    }

    public function changeSearchCategory($category) {
        $this->searchCategory = $category;
    }

    public function changeFilter($filter, $value) {
        $this->selectedFilter = $filter;
        $this->filterValue = $value;
    }

    public function changeSort($sort, $order) {
        $this->selectedSort = $sort;
        $this->selectedSortOrder = $order;
    }

    public function changeGroup($group) {
        $this->selectedGroup = $group;
    }

    public function clearFilters() {
        $this->searchQuery = '';
        $this->selectedFilter = '';
        $this->filterValue = '';
        $this->selectedSort = '';
        $this->selectedSortOrder = 'asc';
        $this->selectedGroup = '';
    }

    public function clearSearch() {
        $this->searchQuery = '';
        $this->searchCategory = '';
    }

    public function clearFilter() {
        $this->selectedFilter = '';
        $this->filterValue = '';
    }

    public function clearSort() {
        $this->selectedSort = '';
        $this->selectedSortOrder = 'asc';
    }

    public function clearGroup() {
        $this->selectedGroup = '';
    }

    public function clearAll() {
        $this->clearFilters();
        $this->clearSearch();
        $this->clearFilter();
        $this->clearSort();
        $this->clearGroup();
    }

    public function resetFilters() {
        $this->reset(['searchQuery', 'searchCategory', 'selectedFilter', 'filterValue', 'selectedSort', 'selectedSortOrder', 'selectedGroup', 'pageSize']);
    }

    public function performAction($action, $items) {
        $this->selectedItems = $items;

        switch ($action) {
            case 'delete':
                $this->dispatch('batchDeleteServiceRoles', [
                    'message' => 'Are you sure you want to delete the selected service roles?'
                ]);
                break;
            case 'export':
                $this->exportSelected();
                break;
            case 'save':
                $this->saveSelected();
                break;
            case 'edit':
                $this->toggleEdit();
                break;
            default:
                break;
        }
    }

    public function toggleEdit() {
        // Implement your logic to enable editing for selected items
        // so each item has a livewire in SvcroleCardItem or SvcroleListItem and have a property called isEditing and a method called editServiceRole and saveServiceRole

        $this->dispatch('toggle-edit-mode', [
            'selectedItems' => $this->selectedItems
        ]);
    }

    public function deleteSelected() {
        if (count($this->selectedItems) > 0) {
            foreach ($this->selectedItems as $id => $selected) {
                if ($selected) {
                    $this->dispatch('svcr-item-delete', $id);
                }
            }
            $url = route('svcroles');
            header("Location: $url");
            exit();
            // $this->render();
        } else {
            $this->dispatch('show-toast', [
                'message' => 'No items selected.',
                'type' => 'warning'
            ]);
        }
    }

    public function saveSelected() {
        $this->dispatch('saveServiceRole', $this->selectedItems);
        // Optionally, show a success message
        $this->toast('Selected service roles saved successfully!', 'success');
    }

    public function exportSelected()
    {
        // You'll need a package like "maatwebsite/excel" for exporting

        // Assuming you have the package installed and configured:
        return response()->streamDownload(function () {
            // echo (new \App\Exports\ServiceRolesExport($this->selectedItems))->download('service_roles.csv')->getFile()->getContent();
        }, 'service_roles.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function toast($msg, $type = 'info') {
        $this->dispatch('show-toast', ['message' => $msg, 'type' => $type]);
    }

    public function openModal($component, $arguments)
    {
        $svcrId = $arguments['serviceRoleId'];
        $this->serviceRoleIdForModal = $svcrId;
        if ($component === 'extra-hour-form') {
            $this->openExtraHourForm();
        }
    }

    public function openExtraHourForm()
    {
        $this->showExtraHourForm = true;
    }

    public function closeModal()
    {
        $this->reset(['showExtraHourForm']);
        $this->serviceRoleIdForModal = null;
    }

    public function updateModalId($data) {
        $id = $data['id'];
        $this->serviceRoleIdForModal = $id;
    }
}
