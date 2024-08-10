<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TeachingAssistant;

class CreateTAModal extends Component
{
    public $tas = [];
    public $showModal = false;
    public $showTAAddedModal = false;

    public function mount()
    {
        $this->resetForm();
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function openTAAddedModal()
    {
        $this->showTAAddedModal = true;
    }

    public function closeTAAddedModal()
    {
        $this->showTAAddedModal = false;
    }

    public function addMore()
    {
        $this->tas[] = ['name' => '', 'rating' => ''];
    }

    public function resetForm()
    {
        $this->tas = [['name' => '', 'rating' => '']];
    }

    public function submit()
    {

        foreach ($this->tas as $taData) {
            TeachingAssistant::create($taData);
        }

        $this->closeModal();
        $this->openTAAddedModal();
    }

    public function render()
    {
        return view('livewire.create-t-a-modal');
    }
}
