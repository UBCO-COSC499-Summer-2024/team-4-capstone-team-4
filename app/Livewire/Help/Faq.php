<?php

namespace App\Livewire\Help;

use Livewire\Component;

class FAQ extends Component
{
    public $faqs;
    protected $placeholders = [
        'support' => [
            'email' => '<a href="mailto:insightatubc@gmail.com">insightatubc@gmail.com</a>',
            'phone' => '<a href="tel:604-822-5555">604-822-5555</a>',
        ],
    ];

    public function mount()
    {
        $this->faqs = json_decode(file_get_contents(base_path('/resources/json/help/faq.json')), true);
        $this->faqs = array_map([$this, 'replacePlaceholders'], $this->faqs);
    }

    // Function to replace placeholders in the FAQ data
    protected function replacePlaceholders($item)
    {
        foreach ($this->placeholders as $section => $values) {
            foreach ($values as $key => $value) {
                $placeholder = '{{ ' . $section . '.' . $key . ' }}';
                $item['answer'] = str_replace($placeholder, $value, $item['answer']);
            }
        }
        return $item;
    }

    public function render()
    {
        return view('livewire.help.faq', [
            'faqs' => $this->faqs,
        ]);
    }
}
