<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImportModal extends Component
{
    public User $user;
    public string $moreText;

    /**
     * Create a new component instance.
     */
    public function __construct(User $user, string $moreText)
    {
        $this->user = $user ? $user : auth()->user();
        $this->moreText = $moreText;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.import-modal', [
            'user' => $this->user,
        ]);
    }
}
