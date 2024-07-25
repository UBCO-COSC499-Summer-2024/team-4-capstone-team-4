<?php

namespace App\Helpers;

class HtmlHelpers
{
    public static function convertSubtopicsToHtml($subtopics, $prefix = 'subtopic')
    {
        $html = '';

        foreach ($subtopics as $index => $subtopic) {
            $subtopicId = $prefix . '-' . $index;
            $html .= '<section id="' . $subtopicId . '" class="p-4 bg-white border rounded-lg">';
            $html .= '<h3 class="mb-2 text-xl font-semibold">' . htmlspecialchars($subtopic['subtopic'] ?? 'No Subtopic') . '</h3>';

            if (!empty($subtopic['subsections'])) {
                $html .= '<div class="space-y-4">';
                foreach ($subtopic['subsections'] as $subIndex => $subsection) {
                    $subsectionId = $subtopicId . '-subsection-' . $subIndex;
                    $html .= '<div id="' . $subsectionId . '" class="p-2 border-l-4 border-blue-500 bg-gray-50">';
                    $html .= '<h4 class="mb-1 text-lg font-medium">' . htmlspecialchars($subsection['heading'] ?? 'No Heading') . '</h4>';
                    $html .= '<p class="mb-2">' . htmlspecialchars($subsection['content'] ?? 'No Content') . '</p>';
                    $html .= '<div class="flex flex-wrap gap-2">';
                    foreach ($subsection['tags'] as $tag) {
                        $html .= '<span class="px-2 py-1 text-sm text-blue-800 bg-blue-100 rounded-full">' . htmlspecialchars($tag) . '</span>';
                    }
                    $html .= '</div>'; // Close tags div
                    $html .= '</div>'; // Close subsection div
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

    public static function convertToJsonToHtml(array $data, string $mainTitle = '')
    {
        $html = '';

        foreach ($data as $index => $topic) {
            $subtopicId = 'subtopic-' . $index;
            $title = $mainTitle ?: ($topic['subtopic'] ?? 'No Subtopic');
            $html .= '<section id="' . $subtopicId . '" class="p-2 mx-1 mb-1 bg-white rounded-lg shadow-xs">';
            $html .= '<h2 class="mb-4 text-2xl font-bold">' . htmlspecialchars($title) . '</h2>';

            if (!empty($topic['subsections'])) {
                $html .= '<div class="space-y-4">';
                foreach ($topic['subsections'] as $subIndex => $subsection) {
                    $subsectionId = $subtopicId . '-subsection-' . $subIndex;
                    $html .= '<div id="' . $subsectionId . '" class="p-2 border-l-4 border-blue-500 bg-gray-50">';
                    $html .= '<h3 class="mb-2 text-lg font-medium">' . htmlspecialchars($subsection['heading'] ?? 'No Heading') . '</h3>';
                    $html .= '<p class="mb-2">' . htmlspecialchars($subsection['content'] ?? 'No Content') . '</p>';
                    $html .= '<div class="flex flex-wrap gap-2">';
                    if (!empty($subsection['tags'])) {
                        foreach ($subsection['tags'] as $tag) {
                            $html .= '<span class="px-2 py-1 text-sm text-blue-800 bg-blue-100 rounded-full">' . htmlspecialchars($tag) . '</span>';
                        }
                    }
                    $html .= '</div>'; // Close tags div
                    $html .= '</div>'; // Close subsection div
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
}
