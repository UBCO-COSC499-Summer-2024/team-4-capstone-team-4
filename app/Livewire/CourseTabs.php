<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Session;

class CourseTabs extends Component
{
    public $tabs = [
        'courses' => [
            'label' => 'Course Sections',
            'component' => 'course-details',
        ],
        'tas' => [
            'label' => 'TAs',
            'component' => 'ta-details',
        ],
        'archive' => [
            'label' => 'Archived Courses',
            'component' => 'archived-details',
        ],
    ];
    #[Session]
    public $activeTab = 'courses';
    protected $listeners = [
        'tab-changed' => 'setActiveTab'
    ];
    public function mount($activeTab = 'courses') {
        $this->setActiveTab($activeTab);
    }
    public function setActiveTab($tab) {
        $this->activeTab = $tab;      
    }
   
    public function render()
    {
        return view('livewire.course-tabs');
    }
}
