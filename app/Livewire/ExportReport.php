<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserRole;

class ExportReport extends Component
{
    public $instructor_id;

    public function mount($instructor_id)
    {
        $this->instructor_id = $instructor_id;
    }

    public function render()
    {
        $instructor = UserRole::find($this->instructor_id);
        
        return view('livewire.export-report', ['instructor' => $instructor]);
    }
}
