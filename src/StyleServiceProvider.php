<?php

namespace Nunophp\Style;

use Illuminate\Support\ServiceProvider;
use Nunophp\Style\Commands\SetupStyleCommand;

class StyleServiceProvider extends ServiceProvider
{
    protected $commands = [
        SetupStyleCommand::class,
    ];

    public function boot(): void
    {
        $this->commands($this->commands);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/stubs/pint.json.stub' => base_path('pint.json'),
                __DIR__.'/../resources/stubs/phpstan.neon.stub' => base_path('phpstan.neon'),
                __DIR__.'/../resources/stubs/rector.php.stub' => base_path('rector.php'),
            ], 'nunophp-style-configs');
        }
    }
}
