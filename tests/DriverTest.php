<?php

use Orchestra\Testbench\TestCase;
use Distilleries\Logentries\Driver;
use Symfony\Component\Debug\Exception\FatalErrorException;

class DriverTest extends TestCase
{
    protected $driver;

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
    public function singletonIsImplemented()
    {
        $driver = Driver::getLogger('token', true, false, LOG_DEBUG);

        $this->assertTrue($this->driver === $driver);
    }

    /** @test */
    public function constructorIsPrivate()
    {
        $reflectionMethod = new ReflectionMethod(Driver::class, '__construct');

        $this->assertTrue($reflectionMethod->isPrivate());
    }

    /** @test */
    public function socketIsNotConnectedBeforeLog()
    {
        $this->assertFalse($this->driver->isConnected());
    }

    /** @test */
    public function socketIsConnectedAfterLog()
    {
        //$this->driver->log('Foo', LOG_DEBUG);
        $this->driver->connectIfNotConnected();

        $this->assertTrue($this->driver->isConnected());   
    }

    /** @test */
    public function useConfigPortAndFallbackToDefaultNonSSL()
    {
        config(['logentries.tcp_port' => 1]);
        $this->assertEquals($this->driver->getPort(), 1);

        config(['logentries.tcp_port' => '']);
        $this->assertEquals($this->driver->getPort(), Driver::LE_PORT);
    }

    /** @test */
    public function useConfigPortAndFallbackToDefaultSSL()
    {
        Driver::tearDown();
        $this->driver = Driver::getLogger('token', true, true, LOG_DEBUG);

        config(['logentries.tls_port' => 2]);
        $this->assertEquals($this->driver->getPort(), 2);

        config(['logentries.tls_port' => '']);
        $this->assertEquals($this->driver->getPort(), Driver::LE_TLS_PORT);
    }

    /** @test */
    public function useDifferentAddressForSSL()
    {
        // Non SSL
        $this->assertEquals($this->driver->getAddress(), Driver::LE_ADDRESS);

        // SSL
        Driver::tearDown();
        $this->driver = Driver::getLogger('token', true, true, LOG_DEBUG);
        $this->assertEquals($this->driver->getAddress(), Driver::LE_TLS_ADDRESS);
    }
}
