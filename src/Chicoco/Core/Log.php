<?php

namespace Chicoco\Core;

class Log
{
    public function info($message)
    {
        if ($message != '') {
            return $this->write($message, LOG_INFO);
        }
    }

    public function warning($message)
    {
        if ($message != '') {
            return $this->write($message, LOG_WARNING);
        }
    }

    public function error($message)
    {
        if ($message != '') {
            return $this->write($message, LOG_ERR);
        }
    }

    protected function write($message, $logLevel)
    {
        return syslog($logLevel, $message);
    }
}
