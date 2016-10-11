<?php

namespace Distilleries\Logentries\Services;

use Closure;
use Distilleries\Logentries\Services\LeLogger;
use Psr\Log\LoggerInterface;

class LogEntriesWritter implements LoggerInterface 
{
    /**
     * LeLogger implementation.
     *
     * @var \LeLogger
     */
    protected $leLogger;

    /**
     * LogEntriesLogger constructor.
     *
     * @param string $token
     * @param bool $persistent
     * @param bool $ssl
     * @param int $severity
     */
    public function __construct(LeLogger $logger)
    {
        $this->leLogger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = [])
    {
        $this->leLogger->log($message, $level);
    }

    /**
     * {@inheritdoc}
     */
    public function alert($message, array $context = [])
    {
        $this->leLogger->Alert($message);
    }

    /**
     * {@inheritdoc}
     */
    public function emergency($message, array $context = [])
    {
        $this->leLogger->Emergency($message);
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = [])
    {
        $this->leLogger->Critical($message);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = [])
    {
        $this->leLogger->Error($message);
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = [])
    {
        $this->leLogger->Warning($message);
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = [])
    {
        $this->leLogger->Notice($message);
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = [])
    {
        $this->leLogger->Info($message);
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = [])
    {
        $this->leLogger->Debug($message);
    }

    /**
     * Setup to handle listen() functions used in LaravelDebugBar.
     *
     * @param \Closure $closure
     * @return void
     */
    public function listen(Closure $closure)
    {
        //
    }
}
