<?php

namespace App\Livewire\Help;

use Livewire\Component;

class Hero extends Component
{
    public $hero_title;
    protected $topics;
    protected $allData;
    public function mount()
    {
        $this->hero_title = "Insight Help Center";
        $topics = json_decode(file_get_contents(public_path('/json/help/index.json')), true);
        // get the topics from the index file and with the url, get the topic data from /json/help/{topic}.json
        $this->allData = [];
        foreach ($topics as $topic) {
            $path = public_path('/json/help/' . $topic['url'] . '.json');
            if (!file_exists($path)) {
                continue;
            }
            $data = json_decode(file_get_contents($path), true);
            $this->allData[$topic['title']] = $data;
        }
        // faq stuff
        $this->allData['FAQ'] = json_decode(file_get_contents(public_path('/json/help/faq.json')), true);



    }
    public function render()
    {
        return view('livewire.help.hero');
    }
}
