<?php

namespace App\Livewire\Header;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Menu extends Component {
    public bool $darkMode;

    protected $listeners = [
        'toggle-dark' => 'toggleDarkMode'
    ];

    public function mount()
    {
        $this->darkMode = session('dark_mode', false);
    }

    public function toggleDarkMode()
    {
        $this->darkMode = !$this->darkMode;
        $theme = $this->darkMode ? 'dark' : 'light';

        if (Auth::check()) {
            Auth::user()->settings->update(['theme' => $theme]);
        }

        session(['dark_mode' => $this->darkMode]);
        // refresh the page or component properly
        $this->dispatch('toggle-dark-mode');

    }

    public function render()
    {
        return view('livewire.header.menu');
    }
}
