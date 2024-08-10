<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

/**
 * A helper class for generating HTML and managing placeholders within content.
 */
class HtmlHelpers {

    /**
     * @var array An associative array storing placeholders and their corresponding values.
     *
     * Placeholders can be nested using dot notation (e.g., 'images.logos.main').
     * Example:
     *  [
     *      'support' => [
     *          'email' => '<a href="mailto:support@example.com">support@example.com</a>',
     *          'phone' => '<a href="tel:+15555551212">+1-555-555-1212</a>',
     *      ],
     *      'images' => [
     *          'test' => '/media/images/ihc_test_image.png',
     *          'logos' => [
     *              'main' => '/images/logo.png',
     *              'footer' => '/images/footer-logo.png'
     *          ]
     *      ],
     *      // ... other placeholders ...
     *  ];
     */
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
        'staff-dropdown-image' => [
            'staff-dropdown' => '/media/images/staff-dropdown.png',
        ]

        // {{ img: staff-dropdown-image.staff-dropdown }}
    ];

    /**
     * @var array Regular expression patterns for different media types to find placeholders.
     *
     * Each media type has an 'all' pattern to capture placeholders in various formats:
     *   - {{ media: placeholder.key }}
     *   - {{ media: "path/to/file.ext" }}
     *   - {{ media: 'path/to/file.ext' }}
     *   - {{ media: placeholder.key }}
     * @warning DO NOT CHANGE PATTERNS
     */
    protected static $patterns = [
        'image' => [
            'all' => '/{{\s*img:?\s*([\w\/\.\-"\']+)\s*}}/',
        ],
        'audio' => [
            'all' => '/{{\s*audio:?\s*([\w\/\.\-"\']+)\s*}}/',
        ],
        'video' => [
            'all' => '/{{\s*video:?\s*([\w\/\.\-"\']+)\s*}}/',
        ],
        'media' => [
            'all' => '/{{\s*media:?\s*([\w\/\.\-"\']+)\s*}}/',
        ],
    ];

    /**
     * Converts an array of subtopics into HTML, recursively handling nested subtopics.
     *
     * @param array $subtopics An array of subtopic data.
     * @param string $prefix (Optional) The prefix to use for subtopic IDs (default: 'subtopic').
     * @return string The generated HTML for the subtopics.
     */
    public static function convertSubtopicsToHtml($subtopics, $prefix = 'subtopic') {
        $html = '';

        foreach ($subtopics as $index => $subtopic) {
            if (!self::userHasAccess($topic['access'] ?? null)) {
                continue;
            }

            $headingSlugST = self::slugify($subtopic['subtopic'] ?? 'no-subtopic'); // Create a slug from the heading
            $subtopicId = $prefix . '-' . $index;
            $html .= '<section id="' . $subtopicId . '" class="p-4 bg-white border rounded-lg">';

            $html .= '<h3 class="mb-2 text-xl font-semibold"><a class="topic-link" href="#' . $headingSlugST . '" class="mb-1 text-lg font-medium no-underline hover:underline">'; // Wrap heading in <a> tag
            $html .= self::processContent($subtopic['subtopic'] ?? 'No Subtopic');
            $html .= '</a></h3>'; // Close the <a> tag
            // $html .= '<h3 class="mb-2 text-xl font-semibold">' . self::replacePlaceholders(htmlspecialchars($subtopic['subtopic'] ?? 'No Subtopic')) . '</h3>';
            // $html .= '<h3 class="mb-2 text-xl font-semibold">' . self::processContent($subtopic['subtopic'] ?? 'No Subtopic') . '</h3>';

            if (!empty($subtopic['subsections'])) {
                $html .= '<div class="space-y-4">';
                foreach ($subtopic['subsections'] as $subIndex => $subsection) {
                    $html .= self::convertSubsectionsToHtml($subsection, $subtopicId);
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

    /**
     * Converts a subsection data array into HTML, recursively handling nested subsections.
     *
     * @param array $subsection The subsection data array.
     * @param string $prefix The prefix to use for subsection IDs.
     * @return string The generated HTML for the subsection.
     */
    private static function convertSubsectionsToHtml($subsection, $prefix)
    {
        $subsectionId = $prefix;
        $headingSlug = self::slugify($prefix.'_'.$subsection['heading'] ?? $prefix.'_no-heading'); // Create a slug from the heading

        $html = '<div id="' . $subsectionId . '" class="p-2 border-l-4 bg-gray-50" style="border-color: var(--secondary-color)">';

        // Make the heading clickable
        $html .= '<a class="topic-link" href="#' . $headingSlug . '" class="mb-1 text-lg font-medium no-underline hover:underline">'; // Wrap heading in <a> tag
        $html .= self::processContent($subsection['heading'] ?? 'No Heading');
        $html .= '</a>'; // Close the <a> tag

        $html .= '<p class="mb-2">' . self::processContent($subsection['content'] ?? 'No Content') . '</p>';
        $html .= '<div class="flex flex-wrap gap-2">';
        foreach ($subsection['tags'] as $tag) {
            $html .= '<span class="px-2 py-1 text-sm text-blue-800 bg-blue-100 rounded-full">' . self::processContent($tag) . '</span>';
        }
        $html .= '</div>'; // Close tags div

        if (!empty($subsection['subsections'])) {
            $html .= '<div class="space-y-4">';
            foreach ($subsection['subsections'] as $subIndex => $nestedSubsection) {
                if (!self::userHasAccess($nestedSubsection['access'] ?? null)) {
                    continue;
                }
                $html .= self::convertSubsectionsToHtml($nestedSubsection, $subsectionId);
            }
            $html .= '</div>'; // Close nested subsections div
        }

        $html .= '</div>'; // Close subsection div
        return $html;
    }

    /**
     * Converts a JSON-like data structure into an HTML representation.
     *
     * @param array $data The data array representing the content structure.
     * @param string $mainTitle (Optional) The main title for the content section.
     * @return string The generated HTML from the data array.
     */
    public static function convertToJsonToHtml(array $data, string $mainTitle = '') {
        $html = '';

        foreach ($data as $index => $topic) {
            if (!self::userHasAccess($topic['access'] ?? null)) {
                continue;
            }
            $subtopicId = $index;
            $title = $mainTitle ?: ($topic['subtopic'] ?? 'No Subtopic');
            $html .= '<section id="' . $subtopicId . '" class="p-2 mx-1 mb-1 bg-white rounded-lg shadow-xs">';
            // $html .= '<h2 class="mb-4 text-2xl font-bold">' . self::replacePlaceholders(htmlspecialchars($title)) . '</h2>';
            $html .= '<h2 class="mb-4 text-2xl font-bold">' . self::processContent($title) . '</h2>';

            if (!empty($topic['subsections'])) {
                $html .= '<div class="space-y-4">';
                foreach ($topic['subsections'] as $subIndex => $subsection) {
                    $html .= self::convertSubsectionsToHtml($subsection, '');
                }
                $html .= '</div>'; // Close subsections div
            }

            if (!empty($topic['subtopics'])) {
                $html .= '<div class="flex flex-col gap-2 mt-2">';
                foreach ($topic['subtopics'] as $subIndex => $subtopic) {
                    $html .= self::convertSubtopicsToHtml([$subtopic], $subtopic['subtopic'] ?? 'No Subtopic');
                }
                $html .= '</div>'; // Close subtopics div
            }

            $html .= '</section>'; // Close subtopic section
        }

        return $html;
    }

    /**
     * Checks if the currently authenticated user has the required access level.
     *
     * @param string|null $access The required access level (e.g., 'admin', 'editor', etc.).
     * @return bool True if the user has access, false otherwise.
     */
    private static function userHasAccess($access) {
        if (is_null($access) || empty($access)) {
            return true;
        }

        $user = Auth::user();
        if (!$user) {
            return false;
        }

        $hierarchy = ['instructor', 'dept_staff', 'dept_head', 'admin'];

        // Check if any of the user's roles meet the access requirement
        foreach ($user->roles as $userRole) {
            if (in_array($userRole, $hierarchy)) {
                $userIndex = array_search($userRole, $hierarchy);
                $accessIndex = array_search($access, $hierarchy);

                if ($userIndex >= $accessIndex) {
                    return true; // User has access with this role
                }
            }
        }

        return false; // No role grants access
    }

    /**
     * Replaces placeholders in the given text with their corresponding values
     * from the $placeholders array and converts Markdown to HTML.
     *
     * @param string $text The text content to process.
     * @return string The processed text with placeholders replaced and Markdown converted.
     */
    private static function processContent($text)
    {
        $text = self::replacePlaceholders($text); // Replace placeholders first
        $text = self::convertMarkdownToHtml($text); // Then convert Markdown
        return $text;
    }


    /**
     * Converts Markdown to HTML using the CommonMark library.
     *
     * @param string $markdown The Markdown content to convert.
     * @return string The converted HTML content.
     */
    private static function convertMarkdownToHtml($markdown)
    {
        // Create a CommonMark environment with desired extensions
        $environment = new Environment([
            'table_of_contents' => [
                'position' => 'top',
                'style' => 'bullet'
            ]
        ]);
        $environment->addExtension(new AutolinkExtension());
        $environment->addExtension(new TableExtension());
        $environment->addExtension(new CommonMarkCoreExtension());

        // Create a Markdown converter using the environment
        $converter = new MarkdownConverter($environment);

        // Convert the Markdown to HTML
        // return $converter->convertToHtml($markdown);
        return $converter->convert($markdown);
    }

    /**
     * Replaces placeholders in the given text with their corresponding values
     * from the $placeholders array.
     *
     * @param string $text The text content to process for placeholder replacement.
     * @return string The text with placeholders replaced by their values.
     */
    public static function replacePlaceholders($text) {
        foreach (array_keys(self::$patterns) as $mediaType) {
            $pattern = self::$patterns[$mediaType]['all']; // Use 'all' pattern directly


            $text = preg_replace_callback($pattern, function ($matches) use ($mediaType) {
                // Debug: Output matches

                $path = trim($matches[1], "'\""); // Handle quotes and extra spaces
                return self::getMediaHtml($mediaType, $path);
            }, $text);
        }

        return $text;
    }

    /**
     * Generates HTML for the given media type and path/placeholder.
     *
     * @param string $type The media type ('img', 'audio', 'video', 'media').
     * @param string $path The path to the media file or a placeholder key.
     * @return string The generated HTML tag for the media.
     */
    private static function getMediaHtml($type, $path) {
        // Normalize type prefix
        $normalizedType = self::normalizeType($type);

        // Check for direct path (e.g., /path/to/image)
        if (preg_match('/^\/.+/', $path)) {
            $resolvedPath = $path;
        } else {
            // Handle placeholder with prefix (e.g., img: prop.prop)
            if (strpos($path, ':') !== false) {
                $parts = explode(':', $path, 2);
                $prefix = trim($parts[0]);
                $key = trim($parts[1]);

                if ($prefix === $normalizedType) {
                    $resolvedPath = self::getNestedValueFromPath(self::$placeholders, $key);
                } else {
                    $resolvedPath = $path;
                }
            } else {
                // Handle placeholder without prefix (e.g., prop.prop)
                // Try type.path first
                $resolvedPath = self::getNestedValueFromPath(self::$placeholders, $normalizedType . $path);

                // If not found, try just path
                if (!$resolvedPath) {
                    $resolvedPath = self::getNestedValueFromPath(self::$placeholders, $path);
                }
            }
        }

        // Generate HTML based on media type
        switch ($type) {
            case 'img':
            case 'image':
                return '<img src="' . htmlspecialchars($resolvedPath) . '" alt="Image">';
            case 'audio':
            case 'sound':
            case 'music':
            case 'podcast':
                return '<audio controls><source src="' . htmlspecialchars($resolvedPath) . '" type="audio/mpeg">Your browser does not support the audio element.</audio>';
            case 'video':
            case 'movie':
            case 'film':
            case 'vid':
                return '<video controls><source src="' . htmlspecialchars($resolvedPath) . '" type="video/mp4">Your browser does not support the video element.</video>';
            case 'media':
                // Determine media type based on extension
                $extension = pathinfo($resolvedPath, PATHINFO_EXTENSION);
                if (in_array($extension, ['mp3', 'wav', 'ogg'])) {
                    return '<audio controls><source src="' . htmlspecialchars($resolvedPath) . '" type="audio/mpeg">Your browser does not support the audio element.</audio>';
                } elseif (in_array($extension, ['mp4', 'webm', 'ogg'])) {
                    return '<video controls><source src="' . htmlspecialchars($resolvedPath) . '" type="video/mp4">Your browser does not support the video element.</video>';
                } else {
                    return '<img src="' . htmlspecialchars($resolvedPath) . '" alt="Image">';
                }
        }

        return '';
    }

    /**
     * Retrieves a nested value from an associative array using dot notation for the path.
     *
     * @param array $array The array to search for the nested value.
     * @param string $path The path to the nested value, using dot notation (e.g., 'key1.key2.targetKey').
     * @return mixed|null The nested value if found, otherwise null.
     */
    private static function getNestedValueFromPath($array, $path) {
        $keys = explode('.', $path);
        foreach ($keys as $key) {
            if (isset($array[$key])) {
                $array = $array[$key];
            } else {
                return null;
            }
        }
        return is_string($array) ? $array : null;
    }

    /**
     * Normalizes the given media type to a standard format.
     *
     * @param string $type The media type to normalize.
     * @return string The normalized media type.
     */
    private static function normalizeType($type) {
        // Define possible type variations
        $typeMap = [
            'img' => 'img',
            'image' => 'img',
            'audio' => 'audio',
            'sound' => 'audio',
            'music' => 'audio',
            'podcast' => 'audio',
            'video' => 'video',
            'movie' => 'video',
            'film' => 'video',
            'vid' => 'video',
            'media' => 'media'
        ];

        return $typeMap[strtolower($type)] ?? $type;
    }

    /**
     * Converts a text string into a slug for use in URLs.
     *
     * @param string $text The text to convert into a slug.
     * @return string The generated slug.
     */
    private static function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
