<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImportConfirmModal extends Component
{
    public $duplicateCourses = [];
    /**
     * Create a new component instance.
     */
    public function __construct(array $duplicateCourses)
    {
        $this->duplicateCourses = $duplicateCourses;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.import-confirm-modal', [
            'duplicateCourses' => $this->duplicateCourses,
        ]);
    }
}
