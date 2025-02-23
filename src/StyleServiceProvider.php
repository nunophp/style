<?php

namespace Nunophp\Style;

use Illuminate\Support\ServiceProvider;
use Nunophp\Style\Commands\SetupStyleCommand;

class StyleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupStyleCommand::class,
            ]);

            // Publish configuration stubs
            $this->publishes([
                __DIR__.'/../resources/stubs/pint.json.stub' => base_path('pint.json'),
                __DIR__.'/../resources/stubs/phpstan.neon.stub' => base_path('phpstan.neon'),
                __DIR__.'/../resources/stubs/rector.php.stub' => base_path('rector.php'),
            ], 'nunophp-style-configs');
        }
    }
}
