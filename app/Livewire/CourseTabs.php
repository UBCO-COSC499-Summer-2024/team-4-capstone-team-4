<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Session;
use Illuminate\Support\Facades\Auth;

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
    ];

    #[Session]
    public $activeTab = 'courses';

    protected $listeners = [
        'tab-changed' => 'setActiveTab'
    ];

    public function mount($activeTab = 'courses') {
        // Check if the user is not an instructor, then add the 'archive' tab
        $user = Auth::user();
        if (!$user->hasRole('instructor')) {
            $this->tabs['archive'] = [
                'label' => 'Archived Courses',
                'component' => 'archived-details',
            ];
        }
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
