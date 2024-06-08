<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Link extends Component {
    /**
     * Create a new component instance.
     */
    public $href;
    public $class;
    public $type;
    public $pg;
    public function __construct($href='#', $class='', $type, $pg) {
        $this->href = $href;
        $this->class = $class;
        $this->type = $type;
        $this->pg = $pg;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.link');
    }
}
