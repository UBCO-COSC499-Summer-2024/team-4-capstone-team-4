<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;

class CustomSearchCourseModal extends Component
{
    public $availableCourses;
    public $filteredCourses;
    public $selectedIndex;
    /**
     * Create a new component instance.
     */
    public function __construct(Collection $availableCourses, Collection $filteredCourses, $selectedIndex)
    {
        $this->availableCourses = $availableCourses;
        $this->filteredCourses = $filteredCourses;
        $this->selectedIndex = $selectedIndex;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.custom-search-course-modal', [
            'availableCourses' => $this->availableCourses,
            'filteredCourses' => $this->filteredCourses,
            'selectedIndex' => $this->selectedIndex,
        ]);
    }
}
