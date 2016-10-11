<?php

namespace Distilleries\Logentries\Services;

use \LeLogger as LG;

class LeLogger extends LG
{

    private $resource = null;

    private $_logToken = null;

    private $severity = LOG_DEBUG;

    private $connectionTimeout;

    private $persistent = true;

    private $use_ssl = false;

    private static $_timestampFormat = 'Y-m-d G:i:s';

    private static $m_instance;

    private $errno;

    private $errstr;

    public static function getLogger($token, $persistent, $ssl, $severity)
    {
        if (!self::$m_instance)
        {
            self::$m_instance = new LeLogger($token, $persistent, $ssl, $severity);
        }

        return self::$m_instance;
    }

    private function __construct($token, $persistent, $use_ssl, $severity)
    {
        $this->validateToken($token);

        $this->_logToken = $token;

        $this->persistent = $persistent;

        $this->use_ssl = $use_ssl;

        $this->severity = $severity;

        $this->connectionTimeout = (float) ini_get('default_socket_timeout');
    }



    public function getPort()
    {
        if ($this->isTLS())
        {
            $port = config('logentries.tls_port');
        }else{
            $port = config('logentries.tcp_port');
        }

        return empty($port)?parent::getPort():$port:
    }


}
