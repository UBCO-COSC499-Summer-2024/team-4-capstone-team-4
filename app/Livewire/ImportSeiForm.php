<?php

namespace App\Livewire;

use App\Models\TestModel;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ImportSeiForm extends Component
{

    use WithPagination;
    use WithFileUploads;

    #[Rule('required|min:2|max:5|unique:test_table,customer_id')]
    public $id = 0;

    #[Rule('required')]
    public $firstname = '';

    #[Rule('required')]
    public $lastname = '';

    #[Rule('required|file|mimes:csv')]
    public $file;



    public function handleClick() {

        $validated = $this->validate();

        TestModel::create([
            'customer_id' => $validated['id'],
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
        ]);

        $this->reset(['id','firstname','lastname']);

        session()->flash('success', 'Successfully Created!');
    }

    public function render()
    {
        $users = TestModel::paginate(5);

        return view('livewire.import-sei-form',[
            'users' => $users,
        ]);
    }
}
