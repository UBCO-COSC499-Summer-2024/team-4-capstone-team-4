<?php

namespace App\Livewire;

use Livewire\Component;

class ImportWorkdayForm extends Component
{

    public $id = '';

    public $firstname = '';

    public $lastname = '';

    public $rows = [];

    public function mount() {
        $this->rows = [
            ['id' => $this->id, 'firstname' => $this->firstname, 'lastname' => $this->lastname],
        ];
    }

    public function addRow() {
        $this->rows[] = ['id' => $this->id, 'firstname' => $this->firstname, 'lastname' => $this->lastname];
    }

    public function deleteRow($row) {
        unset($this->rows[$row]);
        $this->rows = array_values($this->rows); // Reindex array
    }

    public function handleClick() {

    }


    public function render()
    {
        return view('livewire.import-workday-form');
    }
}
