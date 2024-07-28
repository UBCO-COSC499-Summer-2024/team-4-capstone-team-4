<?php

namespace App\Livewire;

use Livewire\Component;

class ServiceRoleTable extends Component
{
    public $svcroles;

    protected $cachedSvcroles = [];

    protected $listeners = [
        'svcr-table-add-row' => 'addRow',
        'svcr-add-table-delete-item' => 'deleteRow',
        'svcr-add-table-undo-delete' => 'undoDelete',
        'svcr-add-table-redo-delete' => 'redoDelete',
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

        $this->dispatch('show-toast', [
            'type' => 'info',
            'message' => 'Row deleted',
        ]);
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
            'updateMe' => false,
            'original_area_name' => '',
            'id' => -1,
        ];
    }

    public function render()
    {
        return view('livewire.service-role-table', [
            'svcroles' => $this->svcroles,
        ]);
    }
}
