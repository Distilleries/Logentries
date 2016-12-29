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
        $this->driver->log($message, LOG_ALERT);
    }

    /**
     * {@inheritdoc}
     */
    public function emergency($message, array $context = [])
    {
        $this->driver->log($message, LOG_EMERG);
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = [])
    {
        $this->driver->log($message, LOG_CRIT);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = [])
    {
        $this->driver->log($message, LOG_ERR);
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = [])
    {
        $this->driver->log($message, LOG_WARNING);
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = [])
    {
        $this->driver->log($message, LOG_NOTICE);
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = [])
    {
        $this->driver->log($message, LOG_INFO);
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = [])
    {
        $this->driver->log($message, LOG_DEBUG);
    }
}
