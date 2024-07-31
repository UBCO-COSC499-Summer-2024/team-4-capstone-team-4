<?php

namespace App\Livewire;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AddServiceRole extends Component
{
    public $links;
    public $showImportModal;
    public $numItems = 0;
    public $viewMode = 'form';
    public $uploadedFiles = [];
    public $formattedData = [];
    public $forceViewMode = false;

    protected $listeners = [
        'clear-imported' => 'clearImported',
    ];

    public function mount($links = []) {
        $this->links = $links;
        if (Session::has('uploadedServiceRoles')) {
            $this->formattedData = session('uploadedServiceRoles');
            $this->numItems = count($this->formattedData);
            $this->viewMode = 'table';
        }
    }

    public function clearImported() {
        Session::forget('uploadedServiceRoles');
        $this->uploadedFiles = [];
        $this->formattedData = [];
        $this->numItems = 0;
        $this->viewMode = 'form';
        // $url = route('svcroles.add');
        // return redirect($url);
    }

    public function render()
    {
        return view('livewire.add-service-role', [
            'links' => $this->links,
            'numItems' => $this->numItems,
            'viewMode' => $this->viewMode,
            'uploadedFiles' => $this->uploadedFiles,
            'formattedData' => $this->formattedData,
            'forceViewMode' => $this->forceViewMode,
        ]);
    }
}
