<?php

namespace App\View\Components\svcrole;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\View\Component;

class Dashboard extends Component
{
    public $page_mode;
    public $view_mode;
    public $serviceroles;
    public $pgn_size;
    public $page;

    /**
     * Create a new component instance.
     */
    public function __construct($pageMode = 'pagination', $viewMode = 'list', $page = 1, $pgnSize = 10)
    {
        $this->page_mode = $pageMode;
        $this->view_mode = $viewMode;
        $this->pgn_size = $pgnSize;
        $this->page = $page;

        $this->getServiceRoles();
    }

    public function getServiceRoles()
    {
        $response = ($this->page_mode == 'pagination')
            ? Http::get('/api/service-roles?page=' . $this->page . '&size=' . $this->pgn_size)
            : Http::get('/api/service-roles');

        $this->serviceroles = json_decode($response->getBody()->getContents(), true)['data'] ?? [];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.svcrole.dashboard', [
            'serviceroles' => $this->serviceroles,
            'page_mode' => $this->page_mode,
            'view_mode' => $this->view_mode
        ]);
    }
}
    