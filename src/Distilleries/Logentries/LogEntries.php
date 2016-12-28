<?php

namespace Distilleries\Logentries;

use Closure;
use Psr\Log\LoggerInterface;

class LogEntries implements LoggerInterface 
{
    /**
     * LogEntries logger driver.
     *
     * @var \Distilleries\Logentries\Driver
     */
    protected $driver;

    /**
     * LogEntries constructor.
     *
     * @param  \Distilleries\Logentries\Driver  $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = [])
    {
        $this->driver->log($message, $level);
    }

    /**
     * {@inheritdoc}
     */
    public function alert($message, array $context = [])
    {
        $this->driver->Alert($message);
    }

    /**
     * {@inheritdoc}
     */
    public function emergency($message, array $context = [])
    {
        $this->driver->Emergency($message);
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = [])
    {
        $this->driver->Critical($message);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = [])
    {
        $this->driver->Error($message);
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = [])
    {
        $this->driver->Warning($message);
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = [])
    {
        $this->driver->Notice($message);
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = [])
    {
        $this->driver->Info($message);
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = [])
    {
        $this->driver->Debug($message);
    }

    /**
     * Setup to handle listen() functions used in LaravelDebugBar.
     *
     * @param  \Closure  $closure
     * @return void
     */
    public function listen(Closure $closure)
    {
        //
    }
}
