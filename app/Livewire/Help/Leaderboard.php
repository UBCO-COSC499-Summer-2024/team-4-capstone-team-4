<?php

namespace App\Livewire\Help;

use Livewire\Component;

/**
 * Leaderboard Component for the Help section
 */
class Leaderboard extends Component {
    public $topic;
    public $data;
    public $slot;

    /**
     * Initialize the component with given parameters.
     *
     * @param string $topic The topic for the leaderboard.
     * @param array $data The data related to the leaderboard.
     * @return void
     */
    public function mount($topic, $data) {
        $this->topic = $topic;
        $this->data = $data;
    }

    /**
     * Render the view associated with this component.
     *
     * @return \Illuminate\View\View
     */
    public function render() {
        return view('livewire.help.leaderboard');
    }
}
