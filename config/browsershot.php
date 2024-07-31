<?php

return [
    'remote_url' => env('BROWSERSHOT_REMOTE_URL', 'insight-chromium'),
    'PATH_CHROME' => env('PATH_CHROME'),
    'PATH_NODE_MODULES' => env('PATH_NODE_MODULES'),
    'HOST' => env('BROWSERSHOT_CHROMIUM_HOST', '127.0.0.1'),
    'NODE' => env('NODE', '/usr/bin/node'),
    'NPM' => env('NPM', '/usr/bin/npm'),
    /*
    |--------------------------------------------------------------------------
    | Chromium path
    |--------------------------------------------------------------------------
    |
    | The path to your Chromium executable.
    |
    */
    'chrome_path' => '/usr/bin/chromium-browser', // Update with your actual path

    /*
    |--------------------------------------------------------------------------
    | Browsershot options
    |--------------------------------------------------------------------------
    |
    | For more information about these options, visit:
    | https://github.com/puppeteer/puppeteer/blob/main/docs/api.md#puppeteerlaunchoptions
    |
    */
    'options' => [
        'args' => [
            // '--no-sandbox', '--disable-setuid-sandbox',
        ],
    ],

];
