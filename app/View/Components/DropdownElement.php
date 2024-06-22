<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DropdownElement extends Component
{
    public $title;
    public $multiple;
    public $searchable;
    public $externalSource;
    public $regex;
    public $values;
    public $preIcon;

    public function __construct(
        $title = 'Dropdown',
        $multiple = false,
        $searchable = false,
        $externalSource = null,
        $regex = 'i',
        $values = [],
        $preIcon = 'list'
    ) {
        $this->title = $title;
        $this->multiple = $multiple;
        $this->searchable = $searchable;
        $this->externalSource = $externalSource;
        $this->regex = $regex;
        $this->values = $values;
        $this->preIcon = $preIcon;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dropdown-element');
    }
}
