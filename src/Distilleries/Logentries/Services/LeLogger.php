<?php

namespace Distilleries\Logentries\Services;

use \LeLogger as LG;

class LeLogger extends LG
{
    

    public function getPort()
    {
        if ($this->isTLS())
        {
            return config('logentries.tls_port',self::LE_TLS_PORT);
        }else{
            return config('logentries.tcp_port',self::LE_PORT);
        }
    }


}
