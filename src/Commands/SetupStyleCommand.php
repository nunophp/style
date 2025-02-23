<?php

namespace Nunophp\Style\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupStyleCommand extends Command
{
    protected $signature = 'style:setup';
    protected $description = 'Sets up a new Laravel project with Nuno Maduro\'s recommended style';

    public function handle()
    {
        $this->info('Starting Nuno Style setup...');

        // Step 1: Install dependencies
        if ($this->confirm('Do you want to install required development tools? (Pest, Pint, PHPStan, Rector)')) {
            $this->info('Installing dependencies...');
            exec('composer require --dev pestphp/pest pestphp/pest-plugin-laravel pestphp/pest-plugin-type-coverage laravel/pint phpstan/phpstan rector/rector');
        }

        // Step 2: Update composer.json
        $this->updateComposerJson();

        // Step 3: Update AppServiceProvider
        if ($this->confirm('Do you want to update AppServiceProvider with recommended configurations? This will replace your existing file.')) {
            $this->updateAppServiceProvider();
        }

        // Step 4: Publish configuration files
        $this->publishConfigs();

        $this->info('Nuno Style setup complete! Run "composer test" to verify.');
    }

    protected function updateComposerJson()
    {
        $composerPath = base_path('composer.json');
        $composer = json_decode(File::get($composerPath), true);

        $newScripts = [
            'lint' => 'pint',
            'test:lint' => 'pint --test',
            'test:types' => 'phpstan analyse  --ansi --memory-limit=2G',
            'test:arch' => 'pest --filter=arch',
            'test:type-coverage' => 'pest --type-coverage --min=100.0 --memory-limit=2G',
            'test:unit' => 'pest --colors=always --coverage --parallel --min=100.0',
            'test' => [
                '@test:lint',
                '@test:types',
                '@test:arch',
                '@test:type-coverage',
                '@test:unit',
            ],
        ];

        $composer['scripts'] = array_merge($composer['scripts'] ?? [], $newScripts);
        File::put($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->info('Updated composer.json with testing scripts.');
    }

    protected function updateAppServiceProvider()
    {
        $stub = File::get(__DIR__ . '/../../resources/stubs/AppServiceProvider.php.stub');
        File::put(app_path('Providers/AppServiceProvider.php'), $stub);
        $this->info('Updated AppServiceProvider with recommended configurations.');
    }

    protected function publishConfigs()
    {
        $files = [
            'pint.json' => File::get(__DIR__ . '/../../resources/stubs/pint.json.stub'),
            'phpstan.neon' => File::get(__DIR__ . '/../../resources/stubs/phpstan.neon.stub'),
            'rector.php' => File::get(__DIR__ . '/../../resources/stubs/rector.php.stub'),
        ];

        foreach ($files as $file => $content) {
            $path = base_path($file);
            if (File::exists($path) && !$this->confirm("File $file already exists. Overwrite?")) {
                continue;
            }
            File::put($path, $content);
            $this->info("Published $file.");
        }
    }
}
