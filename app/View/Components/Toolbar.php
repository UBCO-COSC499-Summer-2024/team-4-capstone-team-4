<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toolbar extends Component
{
    public $items;
    public $orientation;
    /**
     * Create a new component instance.
     */
    public function __construct(array $items, string $orientation = 'horizontal')
    {
        $this->items = $items;
        $this->orientation = $orientation;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.toolbar');
    }
}
