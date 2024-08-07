<?php

namespace App\Livewire;

use Livewire\Component;

class CoursedetailsTableHeader extends Component
{
    public $sortField;
    public $sortDirection;
    public $userRole;
    public function mount($sortField, $sortDirection, $userRole) {
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
        $this->userRole = $userRole;
    }
    public function render()
    {
        return view('livewire.coursedetails-table-header');
    }
}
