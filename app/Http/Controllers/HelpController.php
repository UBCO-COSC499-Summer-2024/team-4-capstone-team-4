<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller {
    public function showHelpPage(Request $request, $topic = null) {
        // Load topics and validate
        $topics = json_decode(file_get_contents(resource_path('/json/help/index.json')), true);
        $currentTopic = $topic;

        // Check if the topic exists
        $validTopic = false;
        // $sendTopic = null;
        foreach ($topics as $t) {
            if ($t['url'] === $currentTopic) {
                $validTopic = true;
                // $sendTopic = $t;
                break;
            }
        }

        if (!$validTopic) {
            // Redirect if the topic is not valid
            return redirect()->route('help');
        }

        // Pass additional parameters if needed
        return view('help-topic', [
            'topics' => $topics,
            'currentTopic' => $currentTopic,
            // 'extraParam' => $request->query('extra_param') // Example extra parameter
        ]);
    }
}
