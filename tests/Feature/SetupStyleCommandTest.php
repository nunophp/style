<?php

use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    File::partialMock();
});

it('runs the style:setup command successfully with all options declined', function (): void {
    File::shouldReceive('get')->with(base_path('composer.json'))->andReturn('{"scripts": {}}');
    File::shouldReceive('exists')->with(base_path('pint.json'))->andReturn(true);
    File::shouldReceive('exists')->with(base_path('phpstan.neon'))->andReturn(true);
    File::shouldReceive('exists')->with(base_path('rector.php'))->andReturn(true);
    File::shouldReceive('put')
        ->withArgs(fn (string $path, string $content): bool => $path === base_path('composer.json'))
        ->once()
        ->andReturn(true);
    File::shouldReceive('put')
        ->with(app_path('Providers/AppServiceProvider.php'))
        ->never();
    File::shouldReceive('put')
        ->with(base_path('pint.json'))
        ->never();
    File::shouldReceive('put')
        ->with(base_path('phpstan.neon'))
        ->never();
    File::shouldReceive('put')
        ->with(base_path('rector.php'))
        ->never();

    $this->artisan('style:setup')
        ->expectsConfirmation('Do you want to install required development tools? (Pest, Pint, PHPStan, Rector)', 'no')
        ->expectsConfirmation('Do you want to update AppServiceProvider with recommended configurations? This will replace your existing file.', 'no')
        ->expectsConfirmation('File pint.json already exists. Overwrite?', 'no')
        ->expectsConfirmation('File phpstan.neon already exists. Overwrite?', 'no')
        ->expectsConfirmation('File rector.php already exists. Overwrite?', 'no')
        ->expectsOutput('Starting Nuno Style setup...')
        ->expectsOutput('Updated composer.json with testing scripts.')
        ->expectsOutput('Nuno Style setup complete! Run "composer test" to verify.')
        ->assertExitCode(0);
});

it('skips overwriting existing config files', function (): void {
    File::shouldReceive('get')->with(base_path('composer.json'))->andReturn('{"scripts": {}}');
    File::shouldReceive('exists')->with(base_path('pint.json'))->andReturn(true);
    File::shouldReceive('exists')->with(base_path('phpstan.neon'))->andReturn(true);
    File::shouldReceive('exists')->with(base_path('rector.php'))->andReturn(true);
    File::shouldReceive('put')
        ->withArgs(fn (string $path, string $content): bool => $path === base_path('composer.json'))
        ->once()
        ->andReturn(true);
    File::shouldReceive('put')
        ->with(base_path('pint.json'))
        ->never();
    File::shouldReceive('put')
        ->with(base_path('phpstan.neon'))
        ->never();
    File::shouldReceive('put')
        ->with(base_path('rector.php'))
        ->never();

    $this->artisan('style:setup')
        ->expectsConfirmation('Do you want to install required development tools? (Pest, Pint, PHPStan, Rector)', 'no')
        ->expectsConfirmation('Do you want to update AppServiceProvider with recommended configurations? This will replace your existing file.', 'no')
        ->expectsConfirmation('File pint.json already exists. Overwrite?', 'no')
        ->expectsConfirmation('File phpstan.neon already exists. Overwrite?', 'yes')
        ->expectsConfirmation('File rector.php already exists. Overwrite?', 'no')
        ->expectsOutput('Starting Nuno Style setup...')
        ->expectsOutput('Updated composer.json with testing scripts.')
        ->expectsOutput('Published phpstan.neon.')
        ->expectsOutput('Nuno Style setup complete! Run "composer test" to verify.')
        ->assertExitCode(0);
});
