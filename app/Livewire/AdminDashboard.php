<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * AdminPages class handles the rendering of the admin pages view in the admin dashboard.
 */
class AdminDashboard extends Component {

    public $pages;

    /**
     * Mount the component and initialize topics.
     *
     * This method is called when the component is initialized. It loads the topics
     * from a JSON file located in the public directory.
     *
     * @return void
     */
    public function mount()
    {
        $this->pages= json_decode(file_get_contents(public_path('/json/pages.json')), true);
    }

    /**
     * Render the admin pages view for the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function render() {
        return view('livewire.admin-dashboard');
    }
}