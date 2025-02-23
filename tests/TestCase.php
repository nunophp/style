<?php

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Nunophp\Style\StyleServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            StyleServiceProvider::class,
        ];
    }
}
