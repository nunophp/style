<?php

namespace Tests;

use Nunophp\Style\StyleServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            StyleServiceProvider::class,
        ];
    }
}
