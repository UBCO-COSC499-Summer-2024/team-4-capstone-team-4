<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateHelpTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:help-template {topic?} {--list} {--sections=} {--force} {--verbatim}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate template JSON files for help topics';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $indexFilePath = resource_path('json/help/index.json');
        if (!File::exists($indexFilePath)) {
            $this->error("Index file not found at: $indexFilePath");
            return;
        }

        $topics = json_decode(File::get($indexFilePath), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Error parsing JSON from index file: " . json_last_error_msg());
            return;
        }

        if ($this->option('list')) {
            $this->listTopics($topics);
            return;
        }

        $topicArg = $this->argument('topic');
        if (!$topicArg) {
            $this->error("Topic argument is required unless using --list option.");
            return;
        }

        // Check if the topic exists
        if ($this->topicExists($topics, $topicArg)) {
            $this->info("Topic already exists: $topicArg");
            $topicFilePath = resource_path("json/help/pages/{$topicArg}.json");
            if (!File::exists($topicFilePath)) {
                $confirm = $this->ask("Help file does not exist for this topic. Do you want to generate one? (y/n)");
                if (strtolower($confirm) !== 'y') {
                    $this->info("Aborting operation.");
                    return;
                }

                $sections = $this->option('sections') ? explode(',', $this->option('sections')) : null;
                $template = $this->generateTemplate($topicArg, $sections);
                File::put($topicFilePath, json_encode($template, JSON_PRETTY_PRINT));
                $this->info("Created template: $topicFilePath");
                return;
            }
            return;
        }

        // Find similar topics using normalized strings
        $similarTopics = $this->findSimilarTopics($topics, $topicArg);
        if (!empty($similarTopics)) {
            $this->line("Did you mean:");
            foreach ($similarTopics as $similarTopic) {
                $this->line("- " . $similarTopic['title']);
            }

            $confirm = $this->ask("Do you want to proceed with the new topic? (y/n)");
            if (strtolower($confirm) !== 'y') {
                $this->info("Aborting operation.");
                return;
            }
        }

        $this->addTopicToIndex($indexFilePath, $topics, $topicArg);
        $topics = json_decode(File::get($indexFilePath), true); // Reload topics after update

        foreach ($topics as $topic) {
            if ($topic['url'] === $topicArg) {
                $topicFilePath = resource_path("json/help/pages/{$topic['url']}.json");
                if (File::exists($topicFilePath) && !$this->option('force')) {
                    $this->info("File already exists: $topicFilePath");
                    return;
                }

                $sections = $this->option('sections') ? explode(',', $this->option('sections')) : null;
                $template = $this->generateTemplate($topic['title'], $sections);
                File::put($topicFilePath, json_encode($template, JSON_PRETTY_PRINT));
                $this->info("Created template: $topicFilePath");
                return;
            }
        }
    }

    protected function topicExists($topics, $topicArg)
    {
        foreach ($topics as $topic) {
            if ($topic['url'] === $topicArg) {
                return true;
            }
        }
        return false;
    }

    protected function addTopicToIndex($indexFilePath, $topics, $topicArg)
    {
        $newTopic = [
            "title" => ucwords(str_replace(['-', '_'], ' ', $topicArg)),
            "url" => $topicArg,
            "icon" => "help_outline" // Default icon, can be customized
        ];

        $topics[] = $newTopic;
        File::put($indexFilePath, json_encode($topics, JSON_PRETTY_PRINT));
        $this->info("Added new topic to index: " . json_encode($newTopic));
    }

    protected function generateTemplate($title, $sections = null)
    {
        $template = [
            [
                "subtopic" => $title,
                "subsections" => [
                    [
                        "heading" => "Introduction",
                        "content" => "This is an introduction to $title.",
                        "tags" => ["intro", "$title"]
                    ],
                    [
                        "heading" => "Details",
                        "content" => "These are the details of $title.",
                        "tags" => ["details", "$title"]
                    ]
                ],
                "subtopics" => [
                    [
                        "subtopic" => "$title Subtopic",
                        "subsections" => [
                            [
                                "heading" => "Subtopic Introduction",
                                "content" => "This is an introduction to $title Subtopic.",
                                "tags" => ["intro", "$title", "subtopic"]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if ($sections) {
            $template[0]['subsections'] = [];
            foreach ($sections as $section) {
                $template[0]['subsections'][] = [
                    "heading" => ucwords(str_replace('_', ' ', $section)),
                    "content" => "This is the $section section of $title.",
                    "tags" => [strtolower($section), "$title"]
                ];
            }
        }

        return $template;
    }

    protected function normalizeString($string)
    {
        return preg_replace('/[\s_\-]+/', ' ', strtolower($string));
    }

    protected function findSimilarTopics($topics, $inputTopic)
    {
        $similarTopics = [];
        $normalizedInput = $this->normalizeString($inputTopic);

        foreach ($topics as $topic) {
            $normalizedTitle = $this->normalizeString($topic['title']);
            $normalizedUrl = $this->normalizeString($topic['url']);
            if (strpos($normalizedTitle, $normalizedInput) !== false || strpos($normalizedUrl, $normalizedInput) !== false) {
                $similarTopics[] = $topic;
            }
        }

        return $similarTopics;
    }

    protected function listTopics($topics)
    {
        $this->info("Existing topics:");
        foreach ($topics as $topic) {
            $this->line("- " . $topic['title'] . " (" . $topic['url'] . ")");
        }
    }
}
