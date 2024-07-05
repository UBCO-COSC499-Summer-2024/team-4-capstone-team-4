<?php

namespace App\Livewire;

use App\Models\Area;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DropdownElement extends Component
{
    public $id;
    public $title;
    public $class;
    public $preIcon = 'list'; // Default pre-icon
    public $values = [];
    public $name = null;
    public $selectedValue = null;
    public $multiple = false;
    public $searchable = false;
    public $useExternal = false;
    public $externalSource;
    public $selectedItems = [];
    public $searchValue = '';
    public $useCustomRegex = false;
    public $regex = 'i';
    public $areas;
    public $formattedAreas;

    protected $listeners = [
        'dropdown-item-selected' => 'handleItemSelected',
        'dropdown-source-loaded' => 'handleExternalDataLoaded',
        'dropdown-source-error' => 'handleExternalDataError',
    ];

    public function mount($id=null, $title, $values = [], $preIcon = null, $name = null, $searchValue = null, $multiple = false, $searchable = false, $useExternal = false, $externalSource = null, $useCustomRegex = false, $regex = 'i')
    {
        $this->id = $id;
        $this->title = $title;
        $this->values = $values;
        $this->preIcon = $preIcon ?? $this->preIcon;
        $this->name = $name;
        $this->searchValue = $searchValue;
        $this->multiple = $multiple;
        $this->searchable = $searchable;
        $this->useExternal = $useExternal;
        $this->externalSource = $externalSource;
        $this->useCustomRegex = $useCustomRegex;
        $this->regex = $regex;
        $this->areas = Area::all();
        // $this->formattedAreas = $this->getFormattedAreasProperty();
    }

    public function getFormattedAreasProperty()
    {
        return $this->areas->mapWithKeys(function ($area) {
            return [ $area->name => ['data' => ['id' => $area->id]] ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.dropdown-element');
    }

    public function toggleDropdown()
    {
    }

    public function handleItemSelected($data = null)
    {
        // if (empty($data)) {

        //     $this->dispatch('dropdown-changed', null);
        //     return;
        // }
        $decodedValue = isset($data['value'])
            ? json_decode($data['value'], true)
            : $data; // If 'value' key doesn't exist, assume $data is the value

        if ($this->multiple) {
            $this->selectedItems = $decodedValue;
            // $this->dispatch('dropdown-changed', $this->selectedItems);
        } else {
            $this->selectedValue = $decodedValue;
            // $this->dispatch('dropdown-changed', $this->selectedValue);
        }
    }

    public function handleSearchInput()
    {
        // No need to handle this in Livewire, JavaScript will filter the items
    }

    public function updatedExternalSource()
    {
        // Trigger JavaScript to load external data
        $this->dispatch('load-external-data', ['source' => $this->externalSource]);
    }

    public function handleExternalDataLoaded($data)
    {
        $this->values = $data['detail'];
    }

    public function handleExternalDataError($error)
    {
        // Handle the error, maybe display an error message
        session()->flash('error', 'Error loading dropdown data.');
    }

    public function getSelectedValues()
    {
        return $this->multiple ? $this->selectedItems : [$this->selectedValue];
    }
}
