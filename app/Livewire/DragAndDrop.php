<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
// use Livewire\WithFileUploads;

class DragAndDrop extends Component
{
    // use WithFileUploads;

    /** 
     * The collection of uploaded files.
     *
     * @var \Illuminate\Support\Collection
     */
    public $files;

    /** 
     * The action to perform on file upload.
     *
     * @var string
     */
    public $action;

    /** 
     * The file types accepted for upload.
     *
     * @var string
     */
    public $accept;

    /** 
     * Indicates whether multiple file uploads are allowed.
     *
     * @var bool
     */
    public $multiple;

    /** 
     * Indicates whether an external handler is used.
     *
     * @var bool
     */
    public $externalHandler;

    /**
     * Initialize the component with default values.
     *
     * @param string|null $action The action to perform on file upload.
     * @param string $accept The file types accepted for upload.
     * @param bool $multiple Whether multiple file uploads are allowed.
     * @param bool $externalHandler Whether an external handler is used.
     * @return void
     */
    public function mount($action = null, $accept = '.csv', $multiple = true, $externalHandler = true) {
        $this->files = collect();
        $this->action = $action ?? 'upload.file';
        $this->accept = $accept;
        $this->multiple = $multiple;
        $this->externalHandler = $externalHandler;
    }

    /**
     * Render the view for the drag-and-drop component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.drag-and-drop', [
            'files' => $this->files,
        ]);
    }
}

