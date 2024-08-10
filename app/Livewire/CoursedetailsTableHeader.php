<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        $canEdit = $user->hasRoles(['admin', 'dept_head', 'dept_staff']);

        return view('livewire.coursedetails-table-header',['canEdit' => $canEdit],);
    }
}
