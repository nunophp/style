<?php

use Nunophp\Style\Commands\SetupStyleCommand;

it('runs the style:setup command successfully with all options declined', function (): void {
    $this->artisan('style:setup')
        ->expectsConfirmation('Do you want to install required development tools? (Pest, Pint, PHPStan, Rector)', 'no')
        ->expectsConfirmation('Do you want to update AppServiceProvider with recommended configurations? This will replace your existing file.', 'no')
        ->expectsConfirmation('File pint.json already exists. Overwrite?', 'no')
        ->expectsConfirmation('File phpstan.neon already exists. Overwrite?', 'no')
        ->expectsConfirmation('File rector.php already exists. Overwrite?', 'no')
        ->assertExitCode(0);
});

it('runs the style:setup command with dependencies and AppServiceProvider updated', function (): void {
    $this->artisan('style:setup')
        ->expectsConfirmation('Do you want to install required development tools? (Pest, Pint, PHPStan, Rector)', 'yes')
        ->expectsConfirmation('Do you want to update AppServiceProvider with recommended configurations? This will replace your existing file.', 'yes')
        ->expectsConfirmation('File pint.json already exists. Overwrite?', 'yes')
        ->expectsConfirmation('File phpstan.neon already exists. Overwrite?', 'yes')
        ->expectsConfirmation('File rector.php already exists. Overwrite?', 'yes')
        ->assertExitCode(0);
});

it('handles invalid composer.json', function (): void {
    $this->mock(SetupStyleCommand::class, function ($mock): void {
        $mock->shouldAllowMockingProtectedMethods()
            ->shouldReceive('decodeJson')
            ->andReturn(null)
            ->shouldReceive('setLaravel')
            ->andReturnSelf(); // Allow setLaravel to pass through
    });
    $this->artisan('style:setup')->assertExitCode(0);
});

it('handles json encoding failure', function (): void {
    $this->mock(SetupStyleCommand::class, function ($mock): void {
        $mock->shouldAllowMockingProtectedMethods()
            ->shouldReceive('encodeJson')
            ->andReturn(false)
            ->shouldReceive('setLaravel')
            ->andReturnSelf(); // Allow setLaravel to pass through
    });
    $this->artisan('style:setup')->assertExitCode(0);
});
