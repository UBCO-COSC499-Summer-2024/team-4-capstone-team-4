<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class panel extends Component
{
    public $panel;
    public $active;
    /**
     * Create a new component instance.
     */
    public function __construct($panel, $active)
    {
        $this->panel = $panel;
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.panel');
    }
}
