<?php

namespace App\Livewire;

use App\Models\Approval;
use App\Models\ApprovalStatus;
use App\Models\ApprovalType;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class ApprovalList extends Component
{
    public $type = 'all'; // Type of approval to display
    public $headers = []; // Table headers
    public $ignore_headers = [
        'created_at', 'updated_at', 'status_id', 'approved_at', 'rejected_at', 'approved_by', 'rejected_by'
    ]; // Headers to ignore in the table
    public $selectedFilter = []; // Selected filters
    public $selectedSort = 'id'; // Column to sort by
    public $filters = []; // Available filters
    public $selectedSortOrder = 'desc'; // Sort order
    public $query = ''; // Search query
    public $perPage = 10; // Number of items per page
    public $page_var = 'page'; // Variable name for pagination
    public $selectAll = false; // Select all items flag
    public $selectedId = false; // Selected item ID for modal
    public $role; // User role
    public $dept; // Department
    public $showApprovalModal = false; // Flag to show/hide approval modal
    public $selectedItems = []; // Selected items
    public $user; // Current user

    public $listeners = [
        'sort-selected' => 'sortSelected', // Listener for sorting
        'change-filters' => 'updateFiltres', // Listener for filter changes
        'clear-filters' => 'clearFilters', // Listener for clearing filters
        'trigger-modal' => 'openModal', // Listener for opening modal
    ];

    /**
     * Initialize component with default settings.
     *
     * @param string $type Type of approval
     * @return void
     */
    public function mount($type) {
        $this->user = User::find(Auth::id());
        $this->type = $type;
        $this->page_var = $type.'_page';
        $this->getHeaders();
        $this->getFilters();
    }

    /**
     * Retrieve and format table headers.
     *
     * @return void
     */
    public function getHeaders() {
        $this->headers = Approval::getColumns();
        $this->headers = collect($this->headers)->map(function ($header) {
            return [
                'name' => $header,
                'sort' => null,
                'filter' => null,
                'type' => 'text',
                'label' => $header === 'id' ? 'ID' : ucwords(str_replace('_', ' ', $header))
            ];
        })->reject(function ($header) {
            if ($this->type === 'all') {
                // Remove specific headers from ignore_headers
                $this->ignore_headers = array_diff($this->ignore_headers, ['status_id', 'approved_at', 'rejected_at', 'approved_by', 'rejected_by']);
            }
            return in_array($header['name'], $this->ignore_headers);
        });
    }

    /**
     * Convert column label to proper column name.
     *
     * @param string $column Column label
     * @return string
     */
    public function getProperColumn($column) {
        return $column === 'ID' ? 'id' : strtolower(str_replace(' ', '_', $column));
    }

    /**
     * Retrieve and format filters based on headers.
     *
     * @return void
     */
    public function getFilters() {
        $this->filters = collect($this->headers)->map(function ($header) {
            $col = $this->getProperColumn($header['label']);
            return [
                'label' => $header['label'],
                'values' => Approval::select($col)->distinct()->get()->pluck($col)
            ];
        })->reject(function ($filter) {
            return in_array($this->getProperColumn($filter['label']), ['created_at', 'updated_at', 'id', 'status_id']);
        });
        $this->filters = collect($this->filters)->mapWithKeys(function ($filter) {
            return [$filter['label'] => $filter['values']];
        });
        foreach ($this->filters as $label => $values) {
            $this->selectedFilter[$label] = [];
        }
    }

    /**
     * Handle sorting when a column is selected.
     *
     * @param string $column Column name
     * @return void
     */
    public function sortSelected($column) {
        $this->sortColumn($column);
    }

    /**
     * Update the search query.
     *
     * @param string $value Search query
     * @return void
     */
    public function updateSearch($value) {
        $this->query = $value;
    }

    /**
     * Clear all selected filters.
     *
     * @return void
     */
    public function clearFilters() {
        $this->selectedFilter = [];
        $this->getFilters();
        foreach ($this->filters as $filter) {
            $this->selectedFilter[$filter['label']] = [];
        }
    }

    /**
     * Update selected filters based on user input.
     *
     * @param string $category Filter category
     * @param mixed $item Filter item
     * @param bool $isChecked Whether the item is checked
     * @return void
     */
    public function updateFiltres($category, $item, $isChecked) {
        if ($this->selectedFilter[$category] === null) {
            $this->selectedFilter[$category] = [];
        }
        if ($isChecked) {
            $this->selectedFilter[$category][] = $item;
        } else {
            $key = array_search($item, $this->selectedFilter[$category]);
            if ($key !== false) {
                unset($this->selectedFilter[$category][$key]);
            }
        }
        $this->selectedFilter[$category] = array_values($this->selectedFilter[$category]);
    }

    /**
     * Open the approval modal with the selected item ID.
     *
     * @param int $id Item ID
     * @return void
     */
    public function openModal($id) {
        $this->selectedId = $id;
        $this->showApprovalModal = true;
    }

    /**
     * Sort the table by the selected column.
     *
     * @param string $column Column name
     * @return void
     */
    public function sortColumn($column) {
        if ($this->selectedSort === $column) {
            $this->selectedSortOrder = $this->selectedSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->selectedSort = $column;
            $this->selectedSortOrder = 'desc';
        }
        $this->dispatch('sort-selected', [$this->selectedSort, $this->selectedSortOrder]);
    }

    /**
     * Render the component with filtered and sorted approvals.
     *
     * @return \Illuminate\View\View
     */
    public function render() {
        $query = Approval::query();

        // Apply search query to the query builder
        if ($this->query) {
            $query->where(function ($q) {
                $q->where('user', 'like', '%'.$this->query.'%')
                    ->orWhere('approval_type', 'like', '%'.$this->query.'%')
                    ->orWhere('approved_by', 'like', '%'.$this->query.'%')
                    ->orWhere('rejected_by', 'like', '%'.$this->query.'%')
                    ->orWhere('details', 'like', '%'.$this->query.'%');
            });
        }

        // Apply selected filters
        foreach($this->selectedFilter as $category => $items) {
            if (!empty($items)) {
                $query->whereIn($this->getProperColumn($category), $items);
            }
        }

        // Apply sorting
        $query->orderBy($this->selectedSort, $this->selectedSortOrder);

        // Filter by approval type if not 'all'
        if ($this->type !== 'all') {
            $approvalType = ApprovalStatus::where('name', $this->type)->first();
            $approvals = $query->where('status_id', $approvalType->id);
        }

        $approvals = $query->paginate($this->perPage);

        return view('livewire.approval-list', [
            'approvals' => $approvals
        ]);
    }

    /**
     * Close the approval modal and reset selected ID and role.
     *
     * @return void
     */
    public function closeApprovalModal() {
        $this->showApprovalModal = false;
        $this->selectedId = null;
        $this->role = null;
    }

    /**
     * Perform an action (approve, reject, or cancel) on the selected approval.
     *
     * @param string $action Action to perform
     * @return void
     */
    public function action($action) {
        try {
            DB::beginTransaction();
            $approval = Approval::find($this->selectedId);
            if ($action === 'approve') {
                if (!$this->role) {
                    throw new \Exception('Please select a user role to assign to this user');
                }
                $existingUR = UserRole::where('user_id', $approval->user_id)->where('role', $this->role)->first();
                if ($existingUR) {
                    throw new \Exception('User already has this role');
                }

                if (($this->role === 'dept_head' || $this->role === 'dept_staff') && !$this->dept) {
                    throw new \Exception('Please select a department to assign to this user');
                }

                $this->validate([
                    'role' => 'required|string|in:dept_head,dept_staff,admin,instructor',
                    'dept' => 'nullable|integer'
                ]);

                $assignedRole = UserRole::create([
                    'user_id' => $approval->user_id,
                    'role' => $this->role,
                    'department_id' => $this->dept
                ]);

                UserRole::audit('create', [
                    'operation_type' => 'CREATE',
                    'new_value' => json_encode($assignedRole->getAttributes()),
                ], $approval->user->getName() . ' assigned to ' . $this->role . ' role by ' . $this->user->getName());
                $approval->approve();
            } else if ($action === 'reject') {
                $approval->reject();
            } else if ($action === 'cancel') {
                $approval->cancel();
            }
            DB::commit();
            $this->closeApprovalModal();
        } catch(\Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
            $message = strtok($message, '\n');

            $this->dispatch('show-toast', ['message' => 'An error occurred', 'type' => 'error']);

            Approval::audit($action.' error', [
                'operation_type' => 'ERROR',
            ], $this->user->getName() . ' encountered an error while trying to ' . $action . ' an approval. \n' . $message);
        }
    }
}
