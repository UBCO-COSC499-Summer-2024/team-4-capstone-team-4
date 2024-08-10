<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TeachingAssistant;

/**
 * Livewire component for managing the creation of Teaching Assistants (TAs) via a modal.
 */
class CreateTAModal extends Component
{
    /**
     * Array to hold Teaching Assistant data for the form.
     *
     * @var array
     */
    public $tas = [];

    /**
     * Flag to determine if the modal is visible.
     *
     * @var bool
     */
    public $showModal = false;

    /**
     * Flag to determine if the TA added confirmation modal is visible.
     *
     * @var bool
     */
    public $showTAAddedModal = false;

    /**
     * Initializes the component and resets the form.
     *
     * @return void
     */
    public function mount()
    {
        $this->resetForm();
    }

    /**
     * Opens the modal for adding Teaching Assistants and resets the form.
     *
     * @return void
     */
    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    /**
     * Closes the modal for adding Teaching Assistants.
     *
     * @return void
     */
    public function closeModal()
    {
        $this->showModal = false;
    }

    /**
     * Opens the confirmation modal after a TA has been added.
     *
     * @return void
     */
    public function openTAAddedModal()
    {
        $this->showTAAddedModal = true;
    }

    /**
     * Closes the confirmation modal.
     *
     * @return void
     */
    public function closeTAAddedModal()
    {
        $this->showTAAddedModal = false;
    }

    /**
     * Adds a new blank TA entry to the form.
     *
     * @return void
     */
    public function addMore()
    {
        $this->tas[] = ['name' => '', 'rating' => ''];
    }

    /**
     * Resets the form to its initial state with one blank TA entry.
     *
     * @return void
     */
    public function resetForm()
    {
        $this->tas = [['name' => '', 'rating' => '']];
    }

    /**
     * Submits the form, creating Teaching Assistant records in the database,
     * then closes the modal and opens the TA added confirmation modal.
     *
     * @return void
     */
    public function submit()
    {
        foreach ($this->tas as $taData) {
            TeachingAssistant::create($taData);
        }

        $this->closeModal();
        $this->openTAAddedModal();
    }

    /**
     * Renders the view for the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.create-t-a-modal');
    }
}

