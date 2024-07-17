<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $filesystem = new Filesystem();
        $service = app_path("Services/{$name}.php");

        if ($filesystem->exists($service)) {
            $this->error("Service {$name} already exists!");
            return;
        }

        $filesystem->ensureDirectoryExists(app_path('Services'));

        $stub = file_get_contents(__DIR__ . '/stubs/service.stub');
        $stub = str_replace('{{serviceName}}', $name, $stub);

        $filesystem->put($service, $stub);

        $this->info("Service {$name} created successfully.");
    }
}
