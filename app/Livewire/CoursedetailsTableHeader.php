<?php

namespace App\Livewire;

use Livewire\Component;

class CoursedetailsTableHeader extends Component
{
    public $sortField;
    public $sortDirection;
    public function mount($sortField, $sortDirection) {
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
    }
    public function render()
    {
        return view('livewire.coursedetails-table-header');
    }
}
