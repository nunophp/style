<?php

namespace Tests;

use Nunophp\Style\StyleServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            StyleServiceProvider::class,
        ];
    }
}
