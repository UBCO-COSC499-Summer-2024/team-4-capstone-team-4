<?php

namespace App\Livewire\Help;

use Livewire\Component;

/**
 * Dashboard Component for the Help section
 */
class Dashboard extends Component {
    public $topic;
    public $data;
    public $slot;

    /**
     * Mount the component with initial dat
     * 
     * @param string $topic The topic for the dashboard
     * @param mixed $data The data related to the topic
     */
    public function mount($topic, $data) {
        $this->topic = $topic;
        $this->data = $data;
    }

    /**
     * Render the component view
     * 
     * @return \Illuminate\View\View The view for the dashboard
     */
    public function render() {
        return view('livewire.help.dashboard');
    }
}

