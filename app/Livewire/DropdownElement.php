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
    public $value = null;
    public $multiple = false;
    public $searchable = false;
    public $useExternal = false;
    public $source = null;
    public $selectedItems = [];
    public $searchValue = '';
    public $useCustomRegex = false;
    public $regex = 'i';

    protected $listeners = [
        'dropdown-item-selected' => 'handleItemSelected',
        'dropdown-source-loaded' => 'handleExternalDataLoaded',
        'dropdown-source-error' => 'handleExternalDataError',
    ];

    public function mount($id=null, $title, $values = [], $preIcon = null, $name = null, $searchValue = null, $multiple = false, $searchable = false, $useExternal = false, $source = null, $useCustomRegex = false, $regex = 'i')
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
        $this->source = $source;
        $this->useCustomRegex = $useCustomRegex;
        $this->regex = $regex;
    }

    public function handleItemSelected($value)
    {
        $this->value = $value;
        $this->dispatch('dropdown-value-updated', $value);
    }

    public function render()
    {
        return view('livewire.dropdown-element');
    }
}
