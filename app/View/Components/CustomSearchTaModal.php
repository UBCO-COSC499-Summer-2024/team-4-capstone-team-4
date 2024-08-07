<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;

class CustomSearchTaModal extends Component
{
    public $availableTas;
    public $filteredTas;
    public $selectedIndex;

    /**
     * Create a new component instance.
     */
    public function __construct(Collection $availableTas, Collection $filteredTas, $selectedIndex)
    {
        $this->availableTas = $availableTas;
        $this->filteredTas = $filteredTas;
        $this->selectedIndex = $selectedIndex;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        // dd($this->filteredTas);
        return view('components.custom-search-ta-modal', [
            'availableTas' => $this->availableTas,
            'filteredTas' => $this->filteredTas,
            'selectedIndex' => $this->selectedIndex,
        ]);
    }
}
