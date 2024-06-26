<?php

namespace App\Livewire;

use App\Models\ServiceRole;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ServiceRolesList extends Component
{
    use WithPagination;
    public $links = [];
    public $columns = [];
    public $viewMode = 'table';
    public $pageMode = 'view';
    public $searchQuery = '';
    public $searchCategory = ''; // column to search
    public $selectedFilter = '';
    public $filterValue = '';
    public $selectedSort = '';
    public $selectedSortOrder = 'asc';
    public $selectedGroup = '';
    public $pageSize = 20;
    protected $queryString = [
        'viewMode',
        'pageMode',
        'searchQuery',
        'selectedFilter',
        'filterValue',
        'selectedSort',
        'selectedSortOrder',
        'selectedGroup',
        'pageSize' => ['except' => 20] // Only update query string if not default
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
    ];

    public function mount($links) {
        $this->links = $links;
    }

    public function getColumns($modelOrTable) {
        if (is_string($modelOrTable) && str_contains($modelOrTable, '.')) {
            // Assume it's a table name (e.g., 'users') or 'table.column'
            [$table] = explode('.', $modelOrTable, 2); // Get table from potential 'table.column'
            return DB::getSchemaBuilder()->getColumnListing($table);
        } elseif (is_object($modelOrTable) && $modelOrTable instanceof \Illuminate\Database\Eloquent\Model) {
            // It's an Eloquent model instance
            return $modelOrTable->getConnection()->getSchemaBuilder()->getColumnListing($modelOrTable->getTable());
        } elseif (is_string($modelOrTable) && class_exists($modelOrTable)) {
            // Assume it's a model class name (e.g., 'App\Models\User')
            $model = new $modelOrTable;
            return $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        } else {
            throw new \InvalidArgumentException('Invalid argument passed to getColumns. Must be a model instance, model class name, or table name.');
        }
    }
    public function render() {
        $serviceRolesQuery = ServiceRole::query();
        if (!empty($this->searchQuery)) {
            $searchableColumns = $this->getColumns(ServiceRole::class); 
    
            $serviceRolesQuery->where(function ($query) use ($searchableColumns) {
                foreach ($searchableColumns as $column) {
                    // Optionally, exclude specific columns from search:
                    // if (!in_array($column, ['password', 'remember_token', /* other columns */])) {
                        $query->orWhere($column, 'like', '%' . $this->searchQuery . '%');
                    // } 
                }
            });
        }

        if (!empty($this->selectedFilter) && !empty($this->filterValue)) {
            $serviceRolesQuery->where($this->selectedFilter, $this->filterValue);
        }

        if (!empty($this->selectedSort)) {
            $serviceRolesQuery->orderBy($this->selectedSort, $this->selectedSortOrder);
        }

        if (!empty($this->selectedGroup)) {
            $serviceRolesQuery->groupBy($this->selectedGroup);
        }

        $serviceRoles = $this->pageMode === 'pagination' ? $serviceRolesQuery->paginate($this->pageSize) : $serviceRolesQuery->get();

        return view('livewire.service-roles-list', ['serviceRoles' => $serviceRoles, 'links' => $this->links]);
    }

    public function changeViewMode($mode) {
        $this->viewMode = $mode;
        $this->render();
    }

    public function changePageMode($mode) {
        $this->pageMode = $mode;
        $this->render();
    }

    public function changePageSize($size) {
        $this->pageSize = $size;
        $this->render();
    }

    public function changeSearchQuery($query) {
        $this->searchQuery = $query;
        $this->render();
    }

    public function changeSearchCategory($category) {
        $this->searchCategory = $category;
        $this->render();
    }

    public function changeFilter($filter, $value) {
        $this->selectedFilter = $filter;
        $this->filterValue = $value;
        $this->render();
    }

    public function changeSort($sort, $order) {
        $this->selectedSort = $sort;
        $this->selectedSortOrder = $order;
        $this->render();
    }

    public function changeGroup($group) {
        $this->selectedGroup = $group;
        $this->render();
    }

    public function clearFilters() {
        $this->searchQuery = '';
        $this->selectedFilter = '';
        $this->filterValue = '';
        $this->selectedSort = '';
        $this->selectedSortOrder = 'asc';
        $this->selectedGroup = '';
        $this->render();
    }

    public function clearSearch() {
        $this->searchQuery = '';
        $this->searchCategory = '';
        $this->render();
    }

    public function clearFilter() {
        $this->selectedFilter = '';
        $this->filterValue = '';
        $this->render();
    }

    public function clearSort() {
        $this->selectedSort = '';
        $this->selectedSortOrder = 'asc';
        $this->render();
    }

    public function clearGroup() {
        $this->selectedGroup = '';
        $this->render();
    }

    public function clearAll() {
        $this->clearFilters();
        $this->clearSearch();
        $this->clearFilter();
        $this->clearSort();
        $this->clearGroup();
        $this->render();
    }

    public function resetFilters() {
        $this->reset(['searchQuery', 'searchCategory', 'selectedFilter', 'filterValue', 'selectedSort', 'selectedSortOrder', 'selectedGroup', 'pageSize']);
        $this->render();
    }
}
