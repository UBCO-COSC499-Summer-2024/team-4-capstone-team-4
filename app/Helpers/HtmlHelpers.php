<?php

namespace App\Helpers;

class HtmlHelpers
{
    protected static $placeholders = [
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

    protected static $patterns = [
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

    public static function convertSubtopicsToHtml($subtopics, $prefix = 'subtopic')
    {
        $html = '';

        foreach ($subtopics as $index => $subtopic) {
            $subtopicId = $prefix . '-' . $index;
            $html .= '<section id="' . $subtopicId . '" class="p-4 bg-white border rounded-lg">';
            $html .= '<h3 class="mb-2 text-xl font-semibold">' . self::replacePlaceholders(htmlspecialchars($subtopic['subtopic'] ?? 'No Subtopic')) . '</h3>';

            if (!empty($subtopic['subsections'])) {
                $html .= '<div class="space-y-4">';
                foreach ($subtopic['subsections'] as $subIndex => $subsection) {
                    $html .= self::convertSubsectionsToHtml($subsection, $subtopicId . '-subsection-' . $subIndex);
                }
                $html .= '</div>'; // Close subsections div
            }

            if (!empty($subtopic['subtopics'])) {
                $html .= '<div class="">';
                $html .= self::convertSubtopicsToHtml($subtopic['subtopics'], $subtopicId);
                $html .= '</div>'; // Close subtopics div
            }

            $html .= '</section>'; // Close subtopic section
        }

        return $html;
    }

    private static function convertSubsectionsToHtml($subsection, $prefix)
    {
        $subsectionId = $prefix;
        $html = '<div id="' . $subsectionId . '" class="p-2 border-l-4 border-blue-500 bg-gray-50">';
        $html .= '<h4 class="mb-1 text-lg font-medium">' . self::replacePlaceholders(htmlspecialchars($subsection['heading'] ?? 'No Heading')) . '</h4>';
        $html .= '<p class="mb-2">' . self::replacePlaceholders(htmlspecialchars($subsection['content'] ?? 'No Content')) . '</p>';
        $html .= '<div class="flex flex-wrap gap-2">';
        foreach ($subsection['tags'] as $tag) {
            $html .= '<span class="px-2 py-1 text-sm text-blue-800 bg-blue-100 rounded-full">' . self::replacePlaceholders(htmlspecialchars($tag)) . '</span>';
        }
        $html .= '</div>'; // Close tags div

        if (!empty($subsection['subsections'])) {
            $html .= '<div class="space-y-4">';
            foreach ($subsection['subsections'] as $subIndex => $nestedSubsection) {
                $html .= self::convertSubsectionsToHtml($nestedSubsection, $subsectionId . '-subsection-' . $subIndex);
            }
            $html .= '</div>'; // Close nested subsections div
        }

        $html .= '</div>'; // Close subsection div
        return $html;
    }

    public static function convertToJsonToHtml(array $data, string $mainTitle = '')
    {
        $html = '';

        foreach ($data as $index => $topic) {
            $subtopicId = 'subtopic-' . $index;
            $title = $mainTitle ?: ($topic['subtopic'] ?? 'No Subtopic');
            $html .= '<section id="' . $subtopicId . '" class="p-2 mx-1 mb-1 bg-white rounded-lg shadow-xs">';
            $html .= '<h2 class="mb-4 text-2xl font-bold">' . self::replacePlaceholders(htmlspecialchars($title)) . '</h2>';

            if (!empty($topic['subsections'])) {
                $html .= '<div class="space-y-4">';
                foreach ($topic['subsections'] as $subIndex => $subsection) {
                    $html .= self::convertSubsectionsToHtml($subsection, $subtopicId . '-subsection-' . $subIndex);
                }
                $html .= '</div>'; // Close subsections div
            }

            if (!empty($topic['subtopics'])) {
                $html .= '<div class="mt-2">';
                foreach ($topic['subtopics'] as $subIndex => $subtopic) {
                    $html .= self::convertSubtopicsToHtml([$subtopic], $subtopic['subtopic'] ?? 'No Subtopic');
                }
                $html .= '</div>'; // Close subtopics div
            }

            $html .= '</section>'; // Close subtopic section
        }

        return $html;
    }

    private static function replacePlaceholders($text)
    {
        $patterns = [
            '/{{\s*img:\s*["\']?(.*?)["\']?\s*}}/' => function ($matches) {
                return '<img src="' . htmlspecialchars($matches[1]) . '" alt="Image">';
            },
            '/{{\s*img\.([a-zA-Z0-9_.]+)\s*}}/' => function ($matches) {
                $propertyPath = $matches[1];
                $path = self::getNestedValue(self::$placeholders['img'], $propertyPath);
                return $path ? '<img src="' . htmlspecialchars($path) . '" alt="Image">' : '';
            },
            '/{{\s*audio:\s*["\']?(.*?)["\']?\s*}}/' => function ($matches) {
                return '<audio controls><source src="' . htmlspecialchars($matches[1]) . '" type="audio/mpeg">Your browser does not support the audio element.</audio>';
            },
            '/{{\s*audio\.([a-zA-Z0-9_.]+)\s*}}/' => function ($matches) {
                $propertyPath = $matches[1];
                $path = self::getNestedValue(self::$placeholders['audio'], $propertyPath);
                return $path ? '<audio controls><source src="' . htmlspecialchars($path) . '" type="audio/mpeg">Your browser does not support the audio element.</audio>' : '';
            },
            '/{{\s*video:\s*["\']?(.*?)["\']?\s*}}/' => function ($matches) {
                return '<video controls><source src="' . htmlspecialchars($matches[1]) . '" type="video/mp4">Your browser does not support the video element.</video>';
            },
            '/{{\s*video\.([a-zA-Z0-9_.]+)\s*}}/' => function ($matches) {
                $propertyPath = $matches[1];
                $path = self::getNestedValue(self::$placeholders['video'], $propertyPath);
                return $path ? '<video controls><source src="' . htmlspecialchars($path) . '" type="video/mp4">Your browser does not support the video element.</video>' : '';
            },
        ];

        foreach ($patterns as $pattern => $callback) {
            $text = preg_replace_callback($pattern, $callback, $text);
        }

        return $text;
    }

    private static function getNestedValue($array, $path)
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
}
