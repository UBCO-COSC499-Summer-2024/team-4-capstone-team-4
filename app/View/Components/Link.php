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
    public $page;
    public $clickAction;
    public $icon;
    public function __construct($type, $page, $icon, $clickAction = null, $class='') {
        $this->class = $class;
        $this->type = $type;
        $this->page = $page;
        $this->icon = $icon;
        $this->clickAction = $clickAction;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.link');
    }
}
