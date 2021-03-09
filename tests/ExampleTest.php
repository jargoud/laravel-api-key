<?php

namespace Jargoud\LaravelApiKey\Tests;

use Orchestra\Testbench\TestCase;
use Jargoud\LaravelApiKey\LaravelApiKeyServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelApiKeyServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
