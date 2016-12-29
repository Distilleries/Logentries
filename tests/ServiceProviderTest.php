<?php

use Orchestra\Testbench\TestCase;
use Distilleries\Logentries\LogentriesServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected $service;

    public function setUp()
    {
        parent::setUp();

        $this->service = $this->app->getProvider(LogentriesServiceProvider::class);
    }

    protected function getPackageProviders($app)
    {
        return [LogentriesServiceProvider::class];
    }

    /** @test */
    public function serviceProviderProvidesLogContainer()
    {
        $facades = $this->service->provides();

        $this->assertTrue(['log'] === $facades);
    }

    /** @test */
    public function serviceProviderRegisterAndBoot()
    {
        $this->service->register();
        $this->service->boot();
    }
}
