<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CoursedetailsTableHeader extends Component
{
    /**
     * The field by which to sort the table.
     *
     * @var string
     */
    public $sortField;

    /**
     * The direction in which to sort the table.
     *
     * @var string
     */
    public $sortDirection;

    /**
     * Initialize component with sorting field and direction.
     *
     * @param string $sortField
     * @param string $sortDirection
     * @return void
     */
    public function mount($sortField, $sortDirection)
    {
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
    }

    /**
     * Render the view for the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $user = Auth::user();
        $canEdit = $user->hasRoles(['admin', 'dept_head', 'dept_staff']);

        return view('livewire.coursedetails-table-header', ['canEdit' => $canEdit]);
    }
}
