<?php

namespace Distilleries\Logentries;

class Driver
{
    /**
     * LogEntries server address for receiving logs.
     *
     * @var string
     */
    const LE_ADDRESS = 'tcp://api.logentries.com';

    /**
     * LogEntries server address for receiving logs via TLS.
     *
     * @var string
     */
    const LE_TLS_ADDRESS = 'tls://api.logentries.com';

    /**
     * LogEntries server port for receiving logs by token.
     * 
     * @var int
     */
    const LE_PORT = 10000;

    /**
     * LogEntries server port for receiving logs with TLS by token.
     * 
     * @var int
     */
    const LE_TLS_PORT = 20000;

    /**
     * Socket resource.
     * 
     * @var resource
     */
    private $resource;

    /**
     * LogEntries configured token.
     * 
     * @var string
     */
    private $logToken;

    /**
     * Log severity level.
     * 
     * @var int
     */
    private $severity = LOG_DEBUG;

    /**
     * Default socket timeout.
     * 
     * @var float
     */
    private $connectionTimeout;

    /**
     * Persist connection flag.
     * 
     * @var bool
     */
    private $persistent = true;

    /**
     * SSL secure flag.
     * 
     * @var bool
     */
    private $useSsl = false;

    /**
     * Timestamp format.
     * 
     * @var string
     */
    private static $timestampFormat = 'Y-m-d G:i:s';

    /**
     * Class singleton.
     *
     * @var static
     */
    private static $instance;

    /**
     * Error code number.
     * 
     * @var int
     */
    private $errNo;

    /**
     * Error string.
     * 
     * @var string
     */
    private $errStr;

    /**
     * Class singleton.
     *
     * @param  string  $token
     * @param  bool  $persistent
     * @param  bool  $useSsl
     * @param  int  $severity
     * @return static
     */
    public static function getLogger($token, $persistent, $useSsl, $severity)
    {
        if (! static::$instance) {
            static::$instance = new Driver($token, $persistent, $useSsl, $severity);
        }

        return static::$instance;
    }

    /**
     * Destroy singleton instance, used in PHPUnit tests.
     * 
     * @return void
     */
    public static function tearDown()
    {
        static::$instance = null;
    }

    /**
     * Driver constructor.
     * 
     * @param  string  $token
     * @param  bool  $persistent
     * @param  bool  $useSsl
     * @param  int  $severity
     */
    private function __construct($token, $persistent, $useSsl, $severity)
    {
        $this->token = $token;

        $this->persistent = $persistent;

        $this->useSsl = $useSsl;

        $this->severity = $severity;

        $this->connectionTimeout = (float) ini_get('default_socket_timeout');
    }

    /**
     * Driver destructor.
     */
    public function __destruct()
    {
        $this->closeSocket();
    }

    /**
     * Log given line with given severity.
     * 
     * @param  string  $line
     * @param  int  $logSeverity
     * @return void
     */
    public function log($line, $logSeverity)
    {
        $this->connectIfNotConnected();

        if ($this->severity >= $logSeverity) {
            $prefix = $this->getPrefix($logSeverity);
            $multiLine = $this->substituteNewLine($line);
            $data = $prefix . $multiLine . PHP_EOL;

            $this->writeToSocket($data);
        }
    }

    /**
     * Open socket connection if not already done.
     *
     * @return void
     */
    public function connectIfNotConnected()
    {
        if (! $this->isConnected()) {
            $this->createSocket();
        }
    }

    /**
     * Return if connection is active.
     *
     * @return bool
     */
    public function isConnected()
    {
        return is_resource($this->resource) && ! feof($this->resource);
    }

    /**
     * Return connection port.
     * 
     * @return int
     */
    public function getPort()
    {
        if ($this->useSsl) {
            $port = config('logentries.tls_port');
            $port = ! empty($port) ? $port : static::LE_TLS_PORT;
        } else {
            $port = config('logentries.tcp_port');
            $port = ! empty($port) ? $port : static::LE_PORT;
        }

        return $port;
    }

    /**
     * Return connection URL.
     * 
     * @return string
     */
    public function getAddress()
    {
        if ($this->useSsl) {
            return static::LE_TLS_ADDRESS;
        } else {
            return static::LE_ADDRESS;
        }
    }

    /**
     * Create socket connection resource if needed.
     *
     * @return void
     */
    private function createSocket()
    {
        $port = $this->getPort();
        $address = $this->getAddress();

        if ($this->persistent) {
            $resource = $this->my_pfsockopen($port, $address);
        } else {
            $resource = $this->my_fsockopen($port, $address);
        }

        if (is_resource($resource) && ! feof($resource)) {
            $this->resource = $resource;
        }
    }

    /**
     * Create persistent socket connection.
     * 
     * @param  int  $port
     * @param  string  $address
     * @return resource
     */
    private function my_pfsockopen($port, $address)
    {
        return @pfsockopen($address, $port, $this->errNo, $this->errStr, $this->connectionTimeout);
    }

    /**
     * Create socket connection.
     * 
     * @param  int  $port
     * @param  string  $address
     * @return resource
     */
    private function my_fsockopen($port, $address)
    {
        return @fsockopen($address, $port, $this->errNo, $this->errStr, $this->connectionTimeout);
    }

    /**
     * Write given line content to socket.
     *
     * @param  string  $line
     * @return void
     */
    private function writeToSocket($line)
    {
        if ($this->isConnected()) {
            fputs($this->resource, $this->token . $line);
        }
    }

    /**
     * Close socket connection and destroy resource.
     * 
     * @return void
     */
    private function closeSocket()
    {
        if (is_resource($this->resource)) {
            fclose($this->resource);
            $this->resource = null;
        }
    }

    /**
     * Substitute new line characters.
     * 
     * @param  string  $line
     * @return string
     */
    private function substituteNewLine($line)
    {
        return str_replace(PHP_EOL, chr(13), $line);
    }

    /**
     * Return prefix (timestamp + level).
     * 
     * @param  int  $level
     * @return string
     */
    private function getPrefix($level)
    {
        $time = date(static::$timestampFormat);

        switch ($level) {
            case LOG_DEBUG:
                return $time . ' DEBUG - ';
            case LOG_INFO:
                return $time . ' INFO - ';
            case LOG_NOTICE:
                return $time . ' NOTICE - ';
            case LOG_WARNING:
                return $time . ' WARN - ';
            case LOG_ERR:
                return $time . ' ERROR - ';
            case LOG_CRIT:
                return $time . ' CRITICAL - ';
            case LOG_ALERT:
                return $time . ' ALERT - ';
            case LOG_EMERG:
                return $time . ' EMERGENCY - ';
            default:
                return $time . ' LOG - ';
        }
    }
}
