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
        'img' => [
            'test' => '/media/images/ihc_test_image.png',
        ],
        'audio' => [
            'sample' => '/media/audio/sample.mp3',
        ],
        'video' => [
            'sample' => '/media/video/sample.mp4',
        ],
    ];

    protected $patterns = [
        'image' => [
            'path' => '/{{\s*img:\s*["\']?(.*?)["\']?\s*}}/',
            'property' => '/{{\s*img\.([a-zA-Z0-9_.]+)\s*}}/',
        ],
        'audio' => [
            'path' => '/{{\s*audio:\s*["\']?(.*?)["\']?\s*}}/',
            'property' => '/{{\s*audio\.([a-zA-Z0-9_.]+)\s*}}/',
        ],
        'video' => [
            'path' => '/{{\s*video:\s*["\']?(.*?)["\']?\s*}}/',
            'property' => '/{{\s*video\.([a-zA-Z0-9_.]+)\s*}}/',
        ],
    ];

    public function mount()
    {
        $this->faqs = json_decode(file_get_contents(base_path('/resources/json/help/faq.json')), true);
        $this->faqs = array_map([$this, 'replacePlaceholders'], $this->faqs);
    }

    protected function replacePlaceholders($item)
    {
        // Handle custom media placeholders first
        $item = $this->replaceMediaPlaceholders($item);

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

    protected function replaceMediaPlaceholders($item)
    {
        $callbacks = $this->getMediaCallbacks();

        if (is_array($item)) {
            array_walk_recursive($item, function (&$v) use ($callbacks) {
                foreach ($this->patterns as $type => $patterns) {
                    foreach ($patterns as $patternType => $pattern) {
                        $v = preg_replace_callback($pattern, $callbacks[$type][$patternType], $v);
                    }
                }
            });
        } else {
            foreach ($this->patterns as $type => $patterns) {
                foreach ($patterns as $patternType => $pattern) {
                    $item = preg_replace_callback($pattern, $callbacks[$type][$patternType], $item);
                }
            }
        }

        return $item;
    }

    protected function getMediaCallbacks()
    {
        return [
            'image' => [
                'path' => function ($matches) {
                    $path = $matches[1];
                    return '<img src="' . $path . '" alt="Image">';
                },
                'property' => function ($matches) {
                    $propertyPath = $matches[1];
                    $path = $this->getNestedValue($this->placeholders['img'], $propertyPath);
                    return $path ? '<img src="' . $path . '" alt="Image">' : '';
                },
            ],
            'audio' => [
                'path' => function ($matches) {
                    $path = $matches[1];
                    return '<audio controls><source src="' . $path . '" type="audio/mpeg">Your browser does not support the audio element.</audio>';
                },
                'property' => function ($matches) {
                    $propertyPath = $matches[1];
                    $path = $this->getNestedValue($this->placeholders['audio'], $propertyPath);
                    return $path ? '<audio controls><source src="' . $path . '" type="audio/mpeg">Your browser does not support the audio element.</audio>' : '';
                },
            ],
            'video' => [
                'path' => function ($matches) {
                    $path = $matches[1];
                    return '<video controls><source src="' . $path . '" type="video/mp4">Your browser does not support the video element.</video>';
                },
                'property' => function ($matches) {
                    $propertyPath = $matches[1];
                    $path = $this->getNestedValue($this->placeholders['video'], $propertyPath);
                    return $path ? '<video controls><source src="' . $path . '" type="video/mp4">Your browser does not support the video element.</video>' : '';
                },
            ],
        ];
    }

    protected function getNestedValue($array, $path)
    {
        $keys = explode('.', $path);
        foreach ($keys as $key) {
            if (is_array($array) && array_key_exists($key, $array)) {
                $array = $array[$key];
            } else {
                return null;
            }
        }
        return $array;
    }

    public function render()
    {
        return view('livewire.help.faq', [
            'faqs' => $this->faqs,
        ]);
    }
}
