<?php

namespace Nunophp\Style\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupStyleCommand extends Command
{
    protected $signature = 'style:setup';

    protected $description = 'Sets up a new Laravel project with Nuno Maduro\'s recommended style';

    public function handle(): void
    {
        $this->info('Starting Nuno Style setup...');

        if ($this->confirm('Do you want to install required development tools? (Pest, Pint, PHPStan, Rector)')) {
            $this->installDependencies();
        }

        $this->updateComposerJson();

        if ($this->confirm('Do you want to update AppServiceProvider with recommended configurations? This will replace your existing file.')) {
            $this->updateAppServiceProvider();
        }

        $this->publishConfigs();

        $this->info('Nuno Style setup complete! Run "composer test" to verify.');
    }

    protected function installDependencies(): void
    {
        $this->info('Installing dependencies...');
        $this->executeCommand('composer require --dev pestphp/pest pestphp/pest-plugin-laravel pestphp/pest-plugin-type-coverage laravel/pint phpstan/phpstan rector/rector');
    }

    protected function executeCommand(string $command): void
    {
        exec($command);
    }

    protected function updateComposerJson(): void
    {
        $composerPath = base_path('composer.json');
        $composer = $this->decodeJson(File::get($composerPath));

        if (! is_array($composer)) {
            $this->error('composer.json is not a valid array');

            return;
        }

        $newScripts = [
            'refacto' => 'rector',
            'lint' => 'pint',
            'test:refacto' => 'rector --dry-run',
            'test:lint' => 'pint --test',
            'test:types' => 'phpstan analyse --ansi --memory-limit=2G',
            'test:arch' => 'pest --filter=arch',
            'test:type-coverage' => 'pest --type-coverage --min=100.0 --memory-limit=2G',
            'test:unit' => 'pest --colors=always --coverage --parallel --min=100.0',
            'test' => [
                '@test:refacto',
                '@test:lint',
                '@test:types',
                '@test:arch',
                '@test:type-coverage',
                '@test:unit',
            ],
        ];

        $composer['scripts'] = array_merge($composer['scripts'] ?? [], $newScripts);
        $encoded = $this->encodeJson($composer);
        if ($encoded === false) {
            $this->error('Failed to encode composer.json');

            return;
        }
        File::put($composerPath, $encoded);
        $this->info('Updated composer.json with testing scripts.');
    }

    protected function decodeJson(string $json): mixed
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function encodeJson(array $data): string|false
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    protected function updateAppServiceProvider(): void
    {
        $stub = File::get(__DIR__.'/../../resources/stubs/AppServiceProvider.php.stub');
        File::put(app_path('Providers/AppServiceProvider.php'), $stub);
        $this->info('Updated AppServiceProvider with recommended configurations.');
    }

    protected function publishConfigs(): void
    {
        $files = [
            'pint.json' => File::get(__DIR__.'/../../resources/stubs/pint.json.stub'),
            'phpstan.neon' => File::get(__DIR__.'/../../resources/stubs/phpstan.neon.stub'),
            'rector.php' => File::get(__DIR__.'/../../resources/stubs/rector.php.stub'),
        ];

        foreach ($files as $file => $content) {
            $path = base_path($file);
            if (File::exists($path) && ! $this->confirm("File $file already exists. Overwrite?")) {
                continue;
            }
            File::put($path, $content);
            $this->info("Published $file.");
        }
    }
}
