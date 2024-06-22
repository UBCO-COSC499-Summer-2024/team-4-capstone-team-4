<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class tabs extends Component
{
    public $groupId;
    public $tabs;
    /**
     * Create a new component instance.
     */
    public function __construct($groupId = null, $tabs = [])
    {
        $this->groupId = $groupId;
        $this->tabs = $tabs;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tabs');
    }
}
