<?php

namespace App\Livewire\Help;

use App\Helpers\HtmlHelpers;
use Livewire\Component;

class FAQ extends Component {
    public $faqs;

    public function mount() {
        $this->faqs = json_decode(file_get_contents(base_path('/resources/json/help/faq.json')), true);
        // $this->faqs = array_map([$this, 'replacePlaceholders'], $this->faqs);
        // use HTMLHelpers::replacePlaceholders instead of $this->replacePlaceholders
        $this->faqs = array_map([HtmlHelpers::class, 'replacePlaceholders'], $this->faqs);
    }

    public function render() {
        return view('livewire.help.faq', [
            'faqs' => $this->faqs,
        ]);
    }
}
