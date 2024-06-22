<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class panels extends Component
{
    public $groupId;
    public $panels;
    /**
     * Create a new component instance.
     */
    public function __construct($groupId = null, $panels = [])
    {
        $this->groupId = $groupId;
        $this->panels = $panels;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.panels');
    }
}
