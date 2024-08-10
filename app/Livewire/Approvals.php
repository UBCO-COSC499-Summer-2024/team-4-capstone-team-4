<?php

namespace App\Livewire;

use App\Models\ApprovalStatus;
use Livewire\Component;

/**
 * Livewire component for managing and displaying approval statuses.
 */
class Approvals extends Component
{
    /**
     * @var array $types List of approval statuses.
     */
    public $types = [];

    /**
     * @var string $viewMode Current view mode ('all', 'grid', 'single').
     */
    public $viewMode = 'all'; // grid/single

    /**
     * @var array $viewing Active types based on view mode.
     *                      - If 'all', this can be empty.
     *                      - If 'grid', all types displayed in a grid format.
     *                      - If 'single', only one type displayed.
     */
    public $viewing = [];

    /**
     * List of events and their corresponding methods.
     *
     * @var array
     */
    protected $listeners = [
        'change-view-mode' => 'changeViewMode',
    ];

    /**
     * Initialize the component.
     * 
     * Fetches all approval statuses and sets the initial viewing mode.
     */
    public function mount() {
        $this->types = ApprovalStatus::all();
        $this->viewing = $this->types;
    }

    /**
     * Handle the change of view mode.
     *
     * @param string $mode The new view mode ('all', 'grid', 'single').
     */
    public function changeViewMode($mode) {
        $this->viewMode = $mode;

        // Update the viewing array based on the new view mode
        if ($mode === 'all') {
            $this->viewing = [];
        } else {
            $this->viewing = [$mode];
        }
    }

    /**
     * Render the component's view.
     *
     * @return \Illuminate\View\View
     */
    public function render() {
        return view('livewire.approvals');
    }
}

