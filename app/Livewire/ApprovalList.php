<?php

namespace App\Livewire;

use App\Models\Approval;
use App\Models\ApprovalStatus;
use App\Models\ApprovalType;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class ApprovalList extends Component
{
    public $approvals = [];
    public $type = 'all';
    public $headers = [];
    public $ignore_headers = [];
    public $selectedFilter = [];
    public $selectedSort;
    public $filters = [];
    public $selectedSortOrder = 'desc';
    public $query;
    public $perPage = 10;
    public $page_var = 'page';
    public $selectAll = false;
    public $selectedItems = [];

    public $listeners = [
        'refresh-list' => 'refresh',
        'sort-selected' => 'sortSelected',
        'filter-selected' => 'filterSelected',
        'filter-cleared' => 'filterCleared',
        'filter-cleared-all' => 'filterClearedAll',
        'filter-applied' => 'filterApplied',
        'filter-applied-all' => 'filterAppliedAll',
        'filter-removed' => 'filterRemoved'
    ];

    public function mount($type) {
        $this->type = $type;
        $this->page_var = $type.'_page';
        $this->getApprovals();
        $this->getHeaders();
        $this->getFilters();
    }

    public function getApprovals() {
        $this->approvals = Approval::all() ?? [];
        if ($this->type !== 'all') {
            $approvalType = ApprovalStatus::where('name', $this->type)->first();
            $this->approvals = $approvalType->approvals ?? [];
        }
    }

    public function getHeaders() {
        $approvalsNotEmpty = ($this->approvals !== [] || !empty($this->approvals) || count($this->approvals) > 0) && !is_null($this->approvals);
        if ($approvalsNotEmpty) {
            $this->headers = collect($this->approvals->first())->keys()->map(function ($header) {
                return [
                    'name' => $header,
                    'sort' => null,
                    'filter' => null,
                    'type' => 'text',
                    'label' => $header === 'id' ? 'ID' : str_replace(' ', '', ucwords(str_replace('_', ' ', $header)))
                ];
            })->reject(function ($header) {
                return in_array($header['name'], $this->ignore_headers);
            });
        } else {
            $this->headers = Approval::getColumns();
            $this->headers = collect($this->headers)->map(function ($header) {
                return [
                    'name' => $header,
                    'sort' => null,
                    'filter' => null,
                    'type' => 'text',
                    'label' => $header === 'id' ? 'ID' : str_replace(' ', '', ucwords(str_replace('_', ' ', $header)))
                ];
            })->reject(function ($header) {
                return in_array($header['name'], $this->ignore_headers);
            });
        }
    }

    public function getFilters() {
        $this->filters = $this->headers->map(function ($header) {
            return [
                'name' => $header['name'],
                'type' => $header['type'],
                'label' => $header['label'],
                'value' => null
            ];
        })
        // ignore the timestamps
        ->reject(function ($filter) {
            return in_array($filter['name'], ['created_at', 'updated_at']);
        });
    }

    public function render()
    {
        return view('livewire.approval-list');
    }
}
