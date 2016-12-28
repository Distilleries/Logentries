<?php

use Mockery;
use Orchestra\Testbench\TestCase;
use Distilleries\Logentries\LogentriesServiceProvider;

class ServiceTest extends TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    protected function getPackageProviders()
    {
        return [LogentriesServiceProvider::class];
    }

    public function testService()
    {
        $service = $this->app->getProvider(LogentriesServiceProvider::class);
        $facades = $service->provides();

        $this->assertTrue(['log'] === $facades);

        $service->boot();
        $service->register();
    }
} 