<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarItem extends Component
{
    public $title;
    public $icon;
    public $href;
    public $target;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $href, $icon, $target = '_self')
    {
        //
        $this->title = $title;
        $this->href = $href;
        $this->icon = $icon;
        $this->target = $target;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar-item');
    }
}
