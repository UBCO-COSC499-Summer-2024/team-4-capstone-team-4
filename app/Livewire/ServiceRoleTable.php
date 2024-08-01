<?php

namespace App\Livewire;

use Livewire\Component;

class ServiceRoleTable extends Component
{
    public $svcroles;

    protected $cachedSvcroles = [];
    protected $updateCounter = 0;

    protected $listeners = [
        'svcr-table-add-row' => 'addRow',
        'svcr-add-table-delete-item' => 'deleteRow',
        'svcr-add-table-undo-delete' => 'undoDelete',
        'svcr-add-table-redo-delete' => 'redoDelete',
        'svcr-add-table-item-updated' => 'itemSaved',
        'svcr-table-save' => 'saveSvcroles',
    ];

    public function mount($svcroles = []) {
        $this->svcroles = count($svcroles) > 0 ? $svcroles['formattedData'] : $svcroles;
        // $this->addRow();
    }

    public function addRow() {
        $newItem = $this->rowTemplate();
        $newItem['id'] = count($this->svcroles) + 1;
        $this->svcroles[] = $newItem;
    }

    public function deleteRow($id) {
        $this->cachedSvcroles = $this->svcroles;
        $this->svcroles = array_values(array_filter($this->svcroles, function($svcrole) use ($id) {
            return $svcrole['id'] != $id;
        }));

        $this->svcroles = array_map(function($svcrole, $index) {
            $svcrole['id'] = $index + 1;
            return $svcrole;
        }, $this->svcroles, array_keys($this->svcroles));

        // $this->dispatch('show-toast', [
        //     'type' => 'info',
        //     'message' => 'Row deleted',
        // ]);
    }

    public function undoDelete() {
        $this->svcroles = $this->cachedSvcroles;
        $this->cachedSvcroles = [];
    }

    public function redoDelete() {
        $this->cachedSvcroles = $this->svcroles;
        $this->svcroles = [];
    }

    public function saveSvcroles() {
        $this->updateCounter = 0;
        $this->dispatch('svcr-add-save-svcroles', $this->svcroles);
    }

    public function rowTemplate() {
        return [
            'name' => '',
            'description' => '',
            'area_id' => '',
            'year' => date('Y'),
            'monthly_hours' => json_encode([
                'January' => 0,
                'February' => 0,
                'March' => 0,
                'April' => 0,
                'May' => 0,
                'June' => 0,
                'July' => 0,
                'August' => 0,
                'September' => 0,
                'October' => 0,
                'November' => 0,
                'December' => 0,
            ]),
            'archived' => false,
            'updateMe' => false,
            'original_area_name' => '',
            'id' => -1,
        ];
    }

    /**
     * Once the item is saved, remove the item from the list
     *
     * @param array $svcrole
     * @return void
     */
    public function itemSaved($svcrole) {
        // Find the index of the item to remove
        $indexToRemove = array_search($svcrole['id'], array_column($this->svcroles, 'id'));

        // If the item exists in the list, remove it
        if ($indexToRemove !== false) {
            unset($this->svcroles[$indexToRemove]);
            $this->svcroles = array_values($this->svcroles); // Reindex array

            $this->updateCounter++;
        }

        if (count($this->svcroles) === 0 && $this->updateCounter > 0) {
            $this->svcroles = [];
            $this->dispatch('clear-imported');
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Service roles saved',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.service-role-table', [
            'svcroles' => $this->svcroles,
        ]);
    }
}
