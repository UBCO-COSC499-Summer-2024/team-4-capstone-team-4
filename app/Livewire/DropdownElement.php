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

    /**
     * Initialize the component with given parameters.
     *
     * @param  int|null  $id
     * @param  string  $title
     * @param  array  $values
     * @param  string|null  $preIcon
     * @param  string|null  $name
     * @param  string|null  $searchValue
     * @param  bool  $multiple
     * @param  bool  $searchable
     * @param  bool  $useExternal
     * @param  string|null  $source
     * @param  bool  $useCustomRegex
     * @param  string  $regex
     * @return void
     */
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
        // $this->mapAttributes(...$attributes);
    }

    // /**
    //  * Map attributes to HTML attributes string.
    //  *
    //  * @param  mixed  ...$attrs
    //  * @return void
    //  */
    // public function mapAttributes(...$attrs)
    // {
    //     $attributes = '';
    //     collect(...$attrs)->each(function ($value, $attr) use (&$attributes) {
    //         $attributes .= " {$attr}=\"{$value}\"";
    //     });
    //     $this->attributes = $attributes;
    // }

    /**
     * Handle the selection of an item from the dropdown.
     *
     * @param  mixed  $value
     * @return void
     */
    public function handleItemSelected($value)
    {
        $this->value = $value;
        $this->dispatch('dropdown-value-updated', $value);
    }

    /**
     * Render the view for the dropdown element component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.dropdown-element');
    }
}
