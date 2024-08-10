<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;

class CustomSearchInstructorModal extends Component
{
    public $availableInstructors;
    public $filteredInstructors;
    public $selectedIndex;

    /**
     * Create a new component instance.
     */
    public function __construct(Collection $availableInstructors, Collection $filteredInstructors, $selectedIndex)
    {
        $this->availableInstructors = $availableInstructors;
        $this->filteredInstructors = $filteredInstructors;
        $this->selectedIndex = $selectedIndex;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.custom-search-instructor-modal', [
            'availableInstructors' => $this->availableInstructors,
            'filteredInstructors' => $this->filteredInstructors,
            'selectedIndex' => $this->selectedIndex
        ]);
    }
}
