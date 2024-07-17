<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
       /*  if (! static::runningInSail()) {
            static::startChromeDriver();
        } */
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver{
        $downloadPath = base_path('tests/Browser/downloads');
        if (!is_dir($downloadPath)) {
            mkdir($downloadPath, 0777, true);
        }
    
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--no-sandbox',
            '--window-size=1920,1080',
        ])->setExperimentalOption('prefs', [
            'download.default_directory' => $downloadPath,
            'download.prompt_for_download' => false,
            'download.directory_upgrade' => true,
            'safebrowsing.enabled' => true,
        ]);
    
        return RemoteWebDriver::create(
            'http://selenium:4444/wd/hub', 
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )->setCapability('acceptInsecureCerts', true)
        );
    }  

}
