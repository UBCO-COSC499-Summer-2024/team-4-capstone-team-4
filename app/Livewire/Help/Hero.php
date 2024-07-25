<?php

namespace App\Livewire\Help;

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
    protected $placeholders = [
        'support' => [
            'email' => '<a href="mailto:insightatubc@gmail.com">insightatubc@gmail.com</a>',
            'phone' => '<a href="tel:604-822-5555">604-822-5555</a>',
        ],
    ];
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
    ];

    public function mount($cache = false) {
        $this->hero_title = "Insight Help Center";
        $this->useCache = $cache;

        $this->allData = $this->useCache
            ? Cache::remember('help_data_cache', now()->addSeconds(1), function () {
                return $this->getAllData();
            })
            : $this->getAllData();

        Log::debug('All data loaded', ['allData' => $this->allData]);
    }

    protected function getAllData()
    {
        $topics = json_decode(File::get(base_path('/resources/json/help/index.json')), true);
        Log::debug('Topics inside cache callback', ['topics' => $topics]);

        $data = [];
        foreach ($topics as $index => $topic) {
            $path = base_path('/resources/json/help/' . $topic['url'] . '.json');
            if (File::exists($path)) {
                $topicData = json_decode(File::get($path), true);
                $data[$topic['title']] = $this->replacePlaceholders($topicData);
                Log::debug('Data inside cache callback', ['data' => $data]);
            }
        }

        $data['FAQ'] = json_decode(File::get(base_path('/resources/json/help/faq.json')), true);
        $data['FAQ'] = $this->replacePlaceholders($data['FAQ']);
        return $data;
    }

    protected function replacePlaceholders($item)
    {
        foreach ($this->placeholders as $section => $values) {
            foreach ($values as $key => $value) {
                $placeholder = '{{ ' . $section . '.' . $key . ' }}';
                $replacement = is_string($value) ? $value : '';

                if (is_array($item)) {
                    array_walk_recursive($item, function (&$v) use ($placeholder, $replacement) {
                        $v = str_replace($placeholder, $replacement, $v);
                    });
                } else {
                    $item = str_replace($placeholder, $replacement, $item);
                }
            }
        }
        return $item;
    }

    public function render()
    {
        return view('livewire.help.hero');
    }

    public function searchV2($q)
    {
        Log::debug('searchV2 method called', ['query' => $q]); // Initial log
        // dd($this->allData);
        try {
            Log::info('Starting search', ['query' => $q]);
            $results = [];

            if ($this->useCache) {
                $cacheKey = 'search_results_' . md5($q . $this->searchInFaqs);
                $results = Cache::remember($cacheKey, now()->addSeconds(1), function () use ($q) {
                    return $this->performSearch($q);
                });
            } else {
                $results = $this->performSearch($q);
            }

            Session::put('searchResults', $results);
            $this->dispatch('helps-search-results', $results);
            Log::info('Search results', ['results' => $results]);
            return $results;

        } catch (\Exception $e) {
            Log::error('Exception during search', ['exception' => $e]);
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'An error occurred while searching. Please try again later. ' . $e->getMessage(),
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
        Log::debug('performSearch method called', ['query' => $q]); // Added log
        $this->searchQuery = strtolower($q);

        if ($this->detectDorking($q)) {
            $this->dorks = $this->parseDorks($q);
        }

        $results = $this->recursiveSearch($this->allData, $this->searchQuery);

        Log::info('Search results', ['results' => $results]);
        return $results;
    }

    protected function parseDorks($query)
{
    Log::debug('parseDorks method called', ['query' => $query]);

    $dorks = [
        'topics' => [],
        'tags' => [],
        'exclude' => [] // Ensure this key is always present
    ];

    foreach ($this->allowedDorks as $dork => $config) {
        foreach ($config['aliases'] as $alias) {
            preg_match_all('/' . $alias . ':\s*"([^"]*)"/i', $query, $matches);
            if (!empty($matches[1])) {
                $dorks[$dork] = array_merge($dorks[$dork], array_map('trim', explode(',', $matches[1][0])));
            }
        }
    }

    Log::info('Parsed dorks', ['dorks' => $dorks]);
    return $dorks;
}



    protected function recursiveSearch($data, $query)
    {
        Log::debug('recursiveSearch method called', ['query' => $query]); // Added log
        // dd($data);
        $results = [];

        foreach ($data as $topic => $items) {
            // dd($items);
            if (is_array($items)) {
                foreach ($items as $item) {
                    if (is_array($item)) {
                        if ($this->matchesDorks($item, $topic)) {
                            $results[] = [
                                'topic' => $topic,
                                'data' => $item,
                            ];
                        }
                        // Recursive call for nested arrays
                        $nestedResults = $this->recursiveSearch($item, $query);
                        $results = array_merge($results, $nestedResults);
                        Log::info('Nested results', ['results' => $nestedResults]);
                    }
                }
            } else {
                if (stripos($items, $query) !== false) {
                    $results[] = [
                        'topic' => $topic,
                        'data' => $items,
                    ];
                    Log::info('Search results', ['results' => $results]);
                }
            }
        }

        return $results;
    }

    protected function matchesDorks($item, $topic)
    {
        Log::debug('matchesDorks method called', ['item' => $item, 'topic' => $topic]);
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
        Log::info('Search match', ['title' => $title, 'content' => $content, 'searchQuery' => $this->searchQuery, 'match' => $matches && $searchMatch]);

        return $matches && $searchMatch;
    }

}
