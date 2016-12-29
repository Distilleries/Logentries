<?php

use Orchestra\Testbench\TestCase;
use Distilleries\Logentries\Driver;

class DriverTest extends TestCase
{
    private $driver;

    public function setUp()
    {
        parent::setUp();

        $this->driver = Driver::getLogger('token', true, false, LOG_DEBUG);
    }

    public function tearDown()
    {
        parent::tearDown();

        Driver::tearDown();
    }

    /** @test */
    public function singleton_is_implemented()
    {
        $driver = Driver::getLogger('token', true, false, LOG_DEBUG);

        $this->assertTrue($this->driver === $driver);
    }

    /** @test */
    public function constructor_is_private()
    {
        $reflectionMethod = new ReflectionMethod(Driver::class, '__construct');

        $this->assertTrue($reflectionMethod->isPrivate());
    }

    /** @test */
    public function socket_is_not_connected_before_log()
    {
        $this->assertFalse($this->driver->isConnected());
    }

    /** @test */
    public function socket_is_connected_after_log()
    {   // @TODO Implements socket stub (for the moment socket layer is considered as "safe")
        //$this->driver->log('Foo', LOG_DEBUG);
        $this->driver->connectIfNotConnected();

        $this->assertTrue($this->driver->isConnected());   
    }

    /** @test */
    public function use_config_port_and_fallback_to_default_non_ssl()
    {
        config(['logentries.tcp_port' => 1]);
        $this->assertEquals($this->driver->getPort(), 1);

        config(['logentries.tcp_port' => '']);
        $this->assertEquals($this->driver->getPort(), Driver::LE_PORT);
    }

    /** @test */
    public function use_config_port_and_fallback_to_default_ssl()
    {
        Driver::tearDown();
        $this->driver = Driver::getLogger('token', true, true, LOG_DEBUG);

        config(['logentries.tls_port' => 2]);
        $this->assertEquals($this->driver->getPort(), 2);

        config(['logentries.tls_port' => '']);
        $this->assertEquals($this->driver->getPort(), Driver::LE_TLS_PORT);
    }

    /** @test */
    public function use_different_address_for_ssl()
    {
        // Non SSL
        $this->assertEquals($this->driver->getAddress(), Driver::LE_ADDRESS);

        // SSL
        Driver::tearDown();
        $this->driver = Driver::getLogger('token', true, true, LOG_DEBUG);
        $this->assertEquals($this->driver->getAddress(), Driver::LE_TLS_ADDRESS);
    }
}
