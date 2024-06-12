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
    public $title;
    public $icon;
    public function __construct($title = "", $icon = "link", $href = '#', $class='') {
        $this->href = $href;
        $this->title = $title;
        $this->icon = $icon;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.link');
    }
}
