<?php

namespace App\Livewire\Help;

use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Results extends Component
{
    public $results = [];

    protected $listeners = [
        'search-results-updated' => 'updateResults',
        'clear-search-results' => 'clearResults'
    ];

    public function mount()
    {
        $this->results = session('searchResults', []);
    }

    public function updateResults()
    {
        $this->results = session('searchResults', []);
    }

    public function clearResults()
    {
        $this->results = [];
        // forget the session variable
        // Session::forget('searchResults');
    }

    public function render()
    {
        return view('livewire.help.results');
    }
}
