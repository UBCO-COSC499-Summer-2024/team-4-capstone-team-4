<?php

namespace App\View\Components\Header;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Menu extends Component
{
    protected $darkMode;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->darkMode = session('dark_mode', false);
    }

    public function toggleDarkMode() {
        $this->darkMode = !$this->darkMode;
        Auth::user()->settings->update(['theme' => $this->darkMode] ? 'dark' : 'light');
        session(['dark_mode' => $this->darkMode]);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header.menu');
    }
}
