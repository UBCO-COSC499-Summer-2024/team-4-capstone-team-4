<?php

namespace App\Livewire;

use App\Exports\SvcroleExport;
use App\Models\ServiceRole;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Area;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

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
    public $pageSize = 20;
    public $selectedItems = [];
    public $showExtraHourForm = false;
    public $user = null;
    protected $validExportOptions = [
        'csv', 'xlsx', 'pdf', 'text', 'print'
    ];
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
        'pageSize' => 'required|integer|min:10',
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
    ];

    public function mount($links = []) {
        $this->links = $links;
        $this->user = User::find(auth()->user()->id);
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

    public function mapColumns($columns) {
        // to the actual column names
        // area => area_id, etc
        $mappedColumns = [];
        foreach ($columns as $column) {
            if ($column === 'area') {
                $mappedColumns[] = 'area_id';
            } else {
                $mappedColumns[] = $column;
            }
        }
        return $mappedColumns;
    }

    public function getColumns($modelOrTable) {
        // /elseif (is_object($modelOrTable) && $modelOrTable instanceof \Illuminate\Database\Eloquent\Model) {
        //     return $modelOrTable->getConnection()->getSchemaBuilder()->getColumnListing($modelOrTable->getTable());
        // } elseif (is_string($modelOrTable) && class_exists($modelOrTable)) {
        //     $model = new $modelOrTable;
        //     return $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        // } else {
        //     throw new \InvalidArgumentException('Invalid argument passed to getColumns. Must be a model instance, model class name, or table name.');
        // }
        // if $modelOrTable is a stirng, convert to model instance, if not model instance, throw exception
        // then check if it has the method getColumns, if not, get it the hard way
        $model = null;
        if (is_string($modelOrTable) && str_contains($modelOrTable, '.')) {
            [$table] = explode('.', $modelOrTable, 2);
            $model = $table;
        } elseif (!is_object($modelOrTable) || !$modelOrTable instanceof \Illuminate\Database\Eloquent\Model) {
            throw new \InvalidArgumentException('Invalid argument passed to getColumns. Must be a model instance, model class name, or table name.');
        } else {
            $model = $modelOrTable;
        }

        if ($model === null) {
            // Log model not found exception
            Log::error('Model not found for getColumns method.');
            return;
        }

        if (method_exists($model, 'getColumns')) {
            return $model::getColumns();
        } else {
            return $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        }
    }

    public function refresh() {
        $this->render();
    }

    public function render() {
        $serviceRolesQuery = ServiceRole::query();
        $user = $this->user;
        $userRole = $user->roles;
        if ($userRole->contains('role', 'dept_head') && !$userRole->contains('role', 'admin')) {
            $deptId = $userRole->where('role', 'dept_head')->first()->department_id;
            // area then department
            $serviceRolesQuery->whereHas('area', function ($query) use ($deptId) {
                $query->where('dept_id', $deptId);
            });
        }

        if (!empty($this->selectedFilter) && !empty($this->filterValue)) {
            if ($this->selectedFilter === 'area' || $this->selectedFilter === 'area_id') {
                $serviceRolesQuery->whereHas('area', function ($query) {
                    $shortCodes = [
                        'COSC' => 'Computer Science',
                        'MATH' => 'Mathematics',
                        'PHYS' => 'Physics',
                        'STAT' => 'Statistics'
                    ];

                    $query->where('name', 'like', '%' . $this->filterValue . '%');
                    foreach ($shortCodes as $shortCode => $areaName) {
                        if (stripos($areaName, $this->filterValue) !== false) {
                            $query->orWhere('name', 'like', '%' . $areaName . '%');
                        }
                    }
                });
            } else {
                $serviceRolesQuery->where($this->selectedFilter, $this->filterValue);
            }
        }

        if (!empty($this->searchQuery)) {
            $searchableColumns = $this->getColumns(ServiceRole::class);
            if(!empty($this->searchQuery)) {
                $serviceRolesQuery->where(function ($query) use ($searchableColumns) {
                    foreach ($searchableColumns as $column) {
                        $query->orWhere($column, 'like', '%' . $this->searchQuery . '%');
                    }
                })
                ->orWhereHas('instructors', function ($query) {
                    $cols = $this->getColumns(User::class);
                    $cols = array_intersect($cols, ['firstname', 'lastname', 'email']);
                    $query->where(function ($query) use ($cols) {
                        foreach ($cols as $col) {
                            $query->orWhere($col, 'like', '%' . $this->searchQuery . '%');
                        }
                    });
                })
                ->orWhereHas('area', function ($query) {
                    $query->where('name', 'like', '%' . $this->searchQuery . '%');
                });
            }
        }

        if (!empty($this->selectedSort)) {
            if (str_contains($this->selectedSort, '.')) {
                [$relation, $column] = explode('.', $this->selectedSort);

                if ($relation === 'area' && $column === 'name') {
                    $serviceRolesQuery->join('areas', 'service_roles.area_id', '=', 'areas.id')
                                     ->orderBy('areas.name', $this->selectedSortOrder);
                } else {
                    $serviceRolesQuery->orderBy(
                        ServiceRole::select($column)
                            ->from('service_roles')
                            ->whereColumn('service_roles.id', 'service_roles.' . $relation . '_id'),
                        $this->selectedSortOrder
                    );
                }
                // $serviceRolesQuery->orderByRelation($relation, $column, $this->selectedSortOrder);

            } else {
                $serviceRolesQuery->orderBy($this->selectedSort, $this->selectedSortOrder);
            }
        } else {
            $serviceRolesQuery->latest();
        }

        if (!empty($this->selectedGroup)) {
            if ($this->selectedGroup === 'area' || $this->selectedGroup === 'area_id') {
                $serviceRolesQuery->join('areas', 'service_roles.area_id', '=', 'areas.id')
                ->select('service_roles.*', 'areas.name as area_name', 'areas.id as area_id')
                ->groupBy('areas.id', 'areas.name', 'service_roles.id');
            } else {
                $serviceRolesQuery->groupBy($this->selectedGroup, 'service_roles.id');
            }
        }

        // if user_role is only instructor don't show archived. put the roles they are assigned to (via role_assignments) to the top.
        // if user_role is dept_head or dept_staff, show archived.
        if ($this->user->hasOnlyRole('instructor')) {
            $serviceRolesQuery->where('archived', false);
        }

        // push role_assignments instructor_id service_roles to the top of the list
        if ($this->user->instructor()) {
            $instructorId = $this->user->instructor()->id;
            $serviceRolesQuery->leftJoin('role_assignments', 'service_roles.id', '=', 'role_assignments.service_role_id')
                ->select('service_roles.*', DB::raw('CASE WHEN role_assignments.instructor_id = ' . $instructorId . ' THEN 1 ELSE 0 END AS is_instructor_role'))
                ->orderBy('is_instructor_role', 'desc')
                ->orderBy('service_roles.name');
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
            case 'archive':
                $this->archive($items);
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

    public function openModal($component)
    {
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
    }

    public function export($as, $options) {
        if (!in_array($as, $this->validExportOptions)) {
            $this->toast('Invalid export format.', 'error');
            return;
        }

        if ($as === 'print') {
            $this->toast('Printig not implemented yet.', 'info');
            return;
        }

        $selectedIds = array_keys(array_filter($this->selectedItems));

        if (empty($selectedIds) && !isset($options['all']) && !isset($options['allExcept'])) {
            $this->toast('No items selected.', 'warning');
            return;
        }

        if (isset($options['all']) && $options['all']) {
            $serviceRoles = ServiceRole::all();
        } elseif (isset($options['selected']) && $options['selected']) {
            $serviceRoles = ServiceRole::whereIn('id', $selectedIds)->get();
        } elseif (isset($options['allExcept']) && is_array($options['allExcept'])) {
            $serviceRoles = ServiceRole::whereNotIn('id', $options['allExcept'])->get();
        }

        if ($as === 'csv' || $as === 'xlsx') {
            return Excel::download(new SvcroleExport($serviceRoles), 'service_roles.' . $as);
        }

        if ($as === 'pdf') {
            return Excel::download(new SvcroleExport($serviceRoles), 'service_roles.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }

        // Assuming you have the package installed and configured:
        return response()->streamDownload(function () use ($as) {
            // echo (new \App\Exports\ServiceRolesExport($this->items))->download('service_roles.' . $as)->getFile()->getContent();
        }, 'service_roles.' . $as, [
            'Content-Type' => 'text/' . $as,
        ]);
    }

    public function archive($selectedIds) {
        DB::beginTransaction();

        try {
            $archivedCount = 0;
            $errors = [];
            $changes = []; // Array to store old and new values

            foreach ($selectedIds as $id => $selected) {
                if ($selected === true) {
                    $serviceRole = ServiceRole::find((int)$id);

                    if ($serviceRole) {
                        $changes[] = [
                            'service_role_id' => $serviceRole->id,
                            'old_value' => $serviceRole->getOriginal(), // Get original attributes
                            'new_value' => array_merge($serviceRole->getOriginal(), ['archived' => !$serviceRole->archived]),
                        ];

                        if ($serviceRole->update(['archived' => !$serviceRole->archived])) {
                            $archivedCount++;
                        } else {
                            $errors[] = $serviceRole->name;
                        }
                    }
                }
            }

            DB::commit();

            $this->toast('Successfully ' . ($archivedCount ? 'archived ' . $archivedCount . ' service roles' : 'updated service roles'), 'success');

            // Log the bulk operation details along with changes
            AuditLog::create([
                'user_id' => (int)auth()->user()->id,
                'user_alt' => User::find((int) auth()->user()->id)->getName(),
                'action' => 'bulk_archive_service_roles',
                'table_name' => 'service_roles',
                'description' => "Archived {$archivedCount} service roles.",
                'old_value' => !empty($changes) ? json_encode($changes) : null, // Store changes for potential restoration
            ]);

            // Log any errors encountered
            if (!empty($errors)) {
                AuditLog::create([
                    'user_id' => (int) auth()->user()->id,
                    'user_alt' => auth()->user()->name,
                    'action' => 'bulk_archive_service_roles_errors',
                    'table_name' => 'service_roles',
                    'description' => "Errors archiving service roles: " . implode(', ', $errors),
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error
            $this->toast('An error occurred: ' . $e->getMessage(), 'error');

            // Log the exception
            AuditLog::create([
                'user_id' => (int) auth()->user()->id,
                'user_alt' => auth()->user()->name,
                'action' => 'bulk_archive_service_roles_exception',
                'table_name' => 'service_roles',
                'description' => "Exception: " . $e->getMessage(),
            ]);
        }
        // reload
        $url = route('svcroles');
        header("Location: $url");
        exit();
    }

}
