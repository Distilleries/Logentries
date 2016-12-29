<?php

use Psr\Log\LoggerInterface;
use Orchestra\Testbench\TestCase;
use Distilleries\Logentries\Driver;
use Distilleries\Logentries\LogEntries;

class LogEntriesTest extends TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function implements_psr_3_interface()
    {
        $this->assertTrue(in_array(LoggerInterface::class, class_implements(LogEntries::class)));
    }

    /** @test */
    public function call_log_driver_on_psr_3_methods()
    {	
    	// Log
    	$mockedDriver = Mockery::mock(Driver::class);
    	$mockedDriver->shouldReceive('log')->with('foo', 'bar')->once();
    	$logEntries = new LogEntries($mockedDriver);
    	$logEntries->log('bar', 'foo');

    	// Alert
    	$mockedDriver = Mockery::mock(Driver::class);
    	$mockedDriver->shouldReceive('log')->with('foo', LOG_ALERT)->once();
    	$logEntries = new LogEntries($mockedDriver);
    	$logEntries->alert('foo');

    	// Emergency
    	$mockedDriver = Mockery::mock(Driver::class);
    	$mockedDriver->shouldReceive('log')->with('foo', LOG_EMERG)->once();
    	$logEntries = new LogEntries($mockedDriver);
    	$logEntries->emergency('foo');

    	// Critical
    	$mockedDriver = Mockery::mock(Driver::class);
    	$mockedDriver->shouldReceive('log')->with('foo', LOG_CRIT)->once();
    	$logEntries = new LogEntries($mockedDriver);
    	$logEntries->critical('foo');

    	// Error
    	$mockedDriver = Mockery::mock(Driver::class);
    	$mockedDriver->shouldReceive('log')->with('foo', LOG_ERR)->once();
    	$logEntries = new LogEntries($mockedDriver);
    	$logEntries->error('foo');

    	// Warning
    	$mockedDriver = Mockery::mock(Driver::class);
    	$mockedDriver->shouldReceive('log')->with('foo', LOG_WARNING)->once();
    	$logEntries = new LogEntries($mockedDriver);
    	$logEntries->warning('foo');

    	// Notice
    	$mockedDriver = Mockery::mock(Driver::class);
    	$mockedDriver->shouldReceive('log')->with('foo', LOG_NOTICE)->once();
    	$logEntries = new LogEntries($mockedDriver);
    	$logEntries->notice('foo');

    	// Info
    	$mockedDriver = Mockery::mock(Driver::class);
    	$mockedDriver->shouldReceive('log')->with('foo', LOG_INFO)->once();
    	$logEntries = new LogEntries($mockedDriver);
    	$logEntries->info('foo');

    	// Debug
    	$mockedDriver = Mockery::mock(Driver::class);
    	$mockedDriver->shouldReceive('log')->with('foo', LOG_DEBUG)->once();
    	$logEntries = new LogEntries($mockedDriver);
    	$logEntries->debug('foo');
    }
}
