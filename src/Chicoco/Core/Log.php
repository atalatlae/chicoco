<?php

namespace Chicoco\Core;

class Log
{
    protected $logLevel = LOG_INFO;
    protected $msgResult;

    public function info($message = '', $controller = '', $action = '')
    {
        if ($message != '') {
            $this->logLevel = LOG_INFO;
            return $this->write($message, $controller, $action);
        }
    }

    public function warning($message = '', $controller = '', $action = '')
    {
        if ($message != '') {
            $this->logLevel = LOG_WARNING;
            return $this->write($message, $controller, $action);
        }
    }

    public function error($message = '', $controller = '', $action = '')
    {
        if ($message != '') {
            $this->logLevel = LOG_ERR;
            return $this->write($message, $controller, $action);
        }
    }

    public function getMsgResult()
    {
        return $this->msgResult;
    }

    protected function write($message = '', $controller = '', $action = '')
    {
        return syslog($this->logLevel, "$controller/$action: $message");
    }
}
