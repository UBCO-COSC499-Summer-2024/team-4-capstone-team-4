<?php

namespace App\Livewire;

use Livewire\Component;

class MonthlyHoursCalendar extends Component
{
    public $year;
    public $months;

    public function mount($year, $months)
    {
        $this->year = $year;
        $this->months = $months;
    }

    public function incrementYear()
    {
        $this->year++;
    }

    public function decrementYear()
    {
        $this->year--;
    }

    public function render()
    {
        return view('livewire.monthly-hours-calendar');
    }

    public function updatedMonths($value, $month)
    {
        // Emit event to validate input in JavaScript
        $this->emit('validateInput', "{$month}-hrs");
    }

}
