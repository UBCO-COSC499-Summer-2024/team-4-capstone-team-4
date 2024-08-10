<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Session;
use Illuminate\Support\Facades\Auth;

class CourseTabs extends Component
{
    /**
     * The tabs configuration array, defining the available tabs and their components.
     *
     * @var array
     */
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

    /**
     * The currently active tab.
     *
     * @var string
     */
    #[Session]
    public $activeTab = 'courses';

    /**
     * The event listeners for the component.
     *
     * @var array
     */
    protected $listeners = [
        'tab-changed' => 'setActiveTab'
    ];

    /**
     * Mount the component with the initial active tab.
     *
     * @param string $activeTab
     * @return void
     */
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

    /**
     * Set the active tab.
     *
     * @param string $tab
     * @return void
     */
    public function setActiveTab($tab) {
        $this->activeTab = $tab;
    }
   
    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.course-tabs');
    }
}

