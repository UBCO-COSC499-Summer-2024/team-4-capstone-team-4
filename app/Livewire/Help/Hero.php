<?php

namespace App\Livewire\Help;

use App\Helpers\HtmlHelpers;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Hero extends Component
{
    public $hero_title;
    public $searchQuery = '';
    public $searchInFaqs = true;
    public $searchResults = [];
    protected $topics;
    public $allData;
    protected $dorks = [];
    protected $allowedDorks = [
        'topics' => [
            'aliases' => ['topic'],
        ],
        'tags' => [
            'aliases' => ['tag'],
        ],
        'exclude' => [
            'aliases' => ['not'],
        ],
    ];
    protected $useCache = false;
    protected $listeners = [
        'search' => 'searchV2',
        'clear-search-results' => 'clearSearchResults',
    ];

    public function mount($cache = false)
    {
        $this->hero_title = "Insight Help Center";
        $this->useCache = $cache;

        $this->allData = $this->useCache
            ? Cache::remember('help_data_cache', now()->addMinutes(60), function () { // Increased cache time for better performance
                return $this->getAllData();
            })
            : $this->getAllData();
    }

    protected function getAllData()
    {
        $topics = json_decode(File::get(base_path('/resources/json/help/index.json')), true);
        $data = [];

        foreach ($topics as $index => $topic) {
            $path = base_path('/resources/json/help/pages/' . $topic['url'] . '.json');
            if (File::exists($path)) {
                $topicData = json_decode(File::get($path), true);
                $data[$topic['title']] = $this->prepareTopicData($topicData, $topic['title']);
            }
        }

        $data['FAQ'] = json_decode(File::get(base_path('/resources/json/help/faq.json')), true);
        $data['FAQ'] = array_map(function ($faq) {
            return HtmlHelpers::replacePlaceholders($faq['question']) .
                   HtmlHelpers::replacePlaceholders($faq['answer']); // Process each string separately
        }, $data['FAQ']);
        return $data;
    }

    // Prepare topic data for search
    protected function prepareTopicData($topicData, $topicTitle)
    {
        $result = [];
        foreach ($topicData as $section) {
            // Add subtopic as a searchable item
            $result[] = [
                'type' => 'subtopic',
                'title' => $section['subtopic'],
                'content' => '', // You can add a brief description here if needed
                'url' => route('help.topic', ['topic' => strtolower(str_replace(' ', '-', $topicTitle))]),
                'tags' => [],
            ];

            // Add subsections
            foreach ($section['subsections'] as $subsection) {
                $result[] = [
                    'type' => 'subsection',
                    'title' => $subsection['heading'],
                    'content' => HtmlHelpers::replacePlaceholders($subsection['content']),
                    'url' => route('help.topic', ['topic' => strtolower(str_replace(' ', '-', $topicTitle)) . '#' . strtolower(str_replace(' ', '-', $subsection['heading']))]),
                    'tags' => $subsection['tags'] ?? [],
                ];
            }

            // Recursively add nested subtopics
            if (isset($section['subtopics'])) {
                $result = array_merge($result, $this->prepareTopicData($section['subtopics'], $topicTitle));
            }
        }
        return $result;
    }

    public function clearSearchResults()
    {
        $this->searchResults = [];
        //browser localstorage clear
        $this->dispatch('clear-search-results');
        Session::forget('searchResults');
    }

    public function render()
    {
        return view('livewire.help.hero');
    }

    public function searchV2($q)
    {
        try {
            $results = [];

            if ($this->useCache) {
                $cacheKey = 'search_results_' . md5($q . $this->searchInFaqs);
                $results = Cache::remember($cacheKey, now()->addSeconds(60), function () use ($q) {
                    return $this->performSearch($q);
                });
            } else {
                $results = $this->performSearch($q);
            }

            // Session::put('searchResults', $results);
            Session::put('searchResults', $results);
            $this->dispatch('help-search-results', $results);
            return $results;

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'An error occurred while searching. Please try again later.',
            ]);
        }
    }

    protected function detectDorking($query)
    {
        foreach ($this->allowedDorks as $dork => $config) {
            foreach ($config['aliases'] as $alias) {
                if (stripos($query, $alias . ':') !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function performSearch($q)
    {
        $this->searchQuery = strtolower($q);

        if ($this->detectDorking($q)) {
            $this->dorks = $this->parseDorks($q);
        }

        $results = $this->recursiveSearch($this->allData, $this->searchQuery);
        return $results;
    }

    protected function parseDorks($query)
    {
        $dorks = [
            'topics' => [],
            'tags' => [],
            'exclude' => [],
        ];

        foreach ($this->allowedDorks as $dork => $config) {
            foreach ($config['aliases'] as $alias) {
                preg_match_all('/' . $alias . ':\s*"([^"]*)"/i', $query, $matches);
                if (!empty($matches[1])) {
                    $dorks[$dork] = array_merge($dorks[$dork], array_map('trim', explode(',', $matches[1][0])));
                }
            }
        }

        return $dorks;
    }

    protected function recursiveSearch($data, $query)
    {
        $results = [];

        foreach ($data as $topic => $items) {
            if ($topic === 'FAQ') {
                // Handle FAQ search
                foreach ($items as $faq) {
                    if (stripos($faq['question'], $query) !== false || stripos($faq['answer'], $query) !== false) {
                        $results[] = [
                            'topic' => 'FAQ',
                            'url' => route('help'),
                            'content' => "<section class='help-result-section'><h2>Question</h2><p>{$faq['question']}</p><h2>Answer</h2><p>{$faq['answer']}</p></section>",
                            'tags' => $faq['tags'] ?? [],
                        ];
                    }
                }
            } else {
                // Handle topic/subsection search
                foreach ($items as $item) {
                    if ($this->matchesDorks($item, $topic)) {
                        $results[] = [
                            'topic' => $topic,
                            'url' => $item['url'], // Use the URL from prepared data
                            'content' => $item['content'],
                            'tags' => $item['tags'] ?? [],
                        ];
                    }
                }
            }
        }
        return $results;
    }

    protected function matchesDorks($item, $topic)
    {
        $title = strtolower($item['title'] ?? '');
        $content = strtolower($item['content'] ?? '');
        $tags = isset($item['tags']) ? array_map('strtolower', $item['tags']) : [];

        $matches = true;

        if (!empty($this->dorks['topics']) && !in_array(strtolower($topic), array_map('strtolower', $this->dorks['topics']))) {
            $matches = false;
        }

        if (!empty($this->dorks['tags']) && empty(array_intersect($this->dorks['tags'], $tags))) {
            $matches = false;
        }

        if ($matches) {
            if (isset($this->dorks['exclude']) && !empty($this->dorks['exclude'])) {
                foreach ($this->dorks['exclude'] as $exclude) {
                    if (stripos($title, $exclude) !== false || stripos($content, $exclude) !== false) {
                        return false;
                    }
                }
            }
        }
        $searchMatch = stripos($title, $this->searchQuery) !== false || stripos($content, $this->searchQuery) !== false;
        return $matches && $searchMatch;
    }
}
