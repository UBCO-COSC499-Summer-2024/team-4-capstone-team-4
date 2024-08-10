<?php

namespace App\Livewire;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AddServiceRole extends Component
{
    /**
     * Array of links for the component.
     *
     * @var array
     */
    public $links;

    /**
     * Flag to determine whether to show the import modal.
     *
     * @var bool
     */
    public $showImportModal;

    /**
     * Number of items in the import.
     *
     * @var int
     */
    public $numItems = 0;

    /**
     * View mode for the component, either 'form' or 'table'.
     *
     * @var string
     */
    public $viewMode = 'form';

    /**
     * Array of uploaded files.
     *
     * @var array
     */
    public $uploadedFiles = [];

    /**
     * Formatted data from uploaded files.
     *
     * @var array
     */
    public $formattedData = [];

    /**
     * Flag to force a specific view mode.
     *
     * @var bool
     */
    public $forceViewMode = false;

    /**
     * Listeners for the component.
     *
     * @var array
     */
    protected $listeners = [
        'clear-imported' => 'clearImported',
    ];

    /**
     * Initialize the component with optional links and session data.
     *
     * @param array $links
     * @return void
     */
    public function mount($links = [])
    {
        $this->links = $links;

        // Check if there is uploaded service roles data in the session
        if (Session::has('uploadedServiceRoles')) {
            $this->formattedData = session('uploadedServiceRoles');
            $this->numItems = count($this->formattedData);
            $this->viewMode = 'table';
        }
    }

    /**
     * Clear the imported service roles data and reset component state.
     *
     * @return void
     */
    public function clearImported()
    {
        Session::forget('uploadedServiceRoles');
        $this->uploadedFiles = [];
        $this->formattedData = [];
        $this->numItems = 0;
        $this->viewMode = 'form';
        // Redirect to the add service role page (commented out)
        // $url = route('svcroles.add');
        // return redirect($url);
    }

    /**
     * Render the view for the component.
     *
     * @return \Illuminate\View\View
     */
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

