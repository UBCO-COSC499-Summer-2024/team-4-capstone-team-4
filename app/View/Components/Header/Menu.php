<?php

namespace App\View\Components\Header;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

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

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header.menu');
    }
}
