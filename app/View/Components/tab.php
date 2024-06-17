<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class tab extends Component
{
    public $title;
    public $active;
    public $id;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $id, $active = false)
    {
        $this->title = $title;
        $this->active = $active;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tab');
    }
}
