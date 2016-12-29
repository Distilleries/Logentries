<?php

use Psr\Log\LoggerInterface;
use Orchestra\Testbench\TestCase;
use Distilleries\Logentries\LogEntries;
use Illuminate\Log\Writer as DefaultLogWriter;
use Illuminate\Contracts\Logging\Log as LogContract;
use Distilleries\Logentries\LogentriesServiceProvider;

class ServiceProviderTest extends TestCase
{
    private $service;

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
    public function service_provider_provides_log_container()
    {
        $facades = $this->service->provides();

        $this->assertTrue(['log'] === $facades);
    }

    /** @test */
    public function service_provider_register_and_boot_logentries()
    {
        config(['logentries.token' => 'token']);

        $this->service->register();
        $this->service->boot();

        $this->assertTrue(get_class($this->app['log']) === LogEntries::class);
    }

    /** @test */
    public function use_default_log_contract_when_no_token_is_configured()
    {
        config(['logentries.token' => '']);

        $this->service->register();
        
        $this->assertTrue(get_class($this->app['log']) === DefaultLogWriter::class);
        $this->assertTrue(in_array(LogContract::class, class_implements($this->app['log'])));
    }
}
