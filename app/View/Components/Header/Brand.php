<?php

namespace App\View\Components\Header;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Brand extends Component
{
    public $brandImg;
    public $brandName;
    public $brandAlt;
    /**
     * Create a new component instance.
     */
    public function __construct($brandName = "Insight", $brandImg = "https://iconape.com/wp-content/files/sf/192229/svg/192229.svg", $brandAlt = "UBC Logo")
    {
        $this->brandName = $brandName;
        $this->brandImg = $brandImg;
        $this->brandAlt = $brandAlt;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header.brand');
    }
}
