<?php

namespace App\Livewire\Help;

use Livewire\Component;

class Results extends Component
{
    public $results = [];

    protected $listeners = [
        // 'updateResults' => 'setResults',
        'search-results-updated' => 'setResults',
    ];

    public function setResults($results)
    {
        $this->results = $results;
    }

    public function render()
    {
        return view('livewire.help.results');
    }
}
