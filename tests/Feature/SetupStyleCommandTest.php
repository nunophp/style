<?php

it('runs the style:setup command successfully', function (): void {
    $this->artisan('style:setup')
        ->expectsConfirmation('Do you want to install required development tools? (Pest, Pint, PHPStan, Rector)', 'no')
        ->expectsConfirmation('Do you want to update AppServiceProvider with recommended configurations? This will replace your existing file.', 'no')
        ->expectsConfirmation('File pint.json already exists. Overwrite?', 'no')
        ->expectsConfirmation('File phpstan.neon already exists. Overwrite?', 'no')
        ->expectsConfirmation('File rector.php already exists. Overwrite?', 'no')
        ->assertExitCode(0);
});
