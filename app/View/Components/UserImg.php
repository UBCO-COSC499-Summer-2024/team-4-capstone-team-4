<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserImg extends Component
{
    public $imgUrl;
    /**
     * Create a new component instance.
     */
    public function __construct($imgUrl = "https://images.unsplash.com/photo-1520333789090-1afc82db536a?crop=entropy&amp;cs=tinysrgb&amp;fit=max&amp;fm=jpg&amp;ixid=M3wzNjMxMDZ8MHwxfHJhbmRvbXx8fHx8fHx8fDE3MTY1ODY5ODd8&amp;ixlib=rb-4.0.3&amp;q=80&amp;w=1080")
    {
        $this->imgUrl = $imgUrl;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-img');
    }
}
