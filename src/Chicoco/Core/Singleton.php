<?php

namespace Chicoco\Core;

class Singleton
{
    private static $instance = [];

    private function __construct()
    {
    }

    public static function getInstance()
    {
        $calledClass = get_called_class();

        if (empty(self::$instance[$calledClass])) {
            self::$instance[$calledClass] = new $calledClass;
        }
        return self::$instance[$calledClass];
    }

    public function __clone()
    {
        trigger_error('Clone is not allowed', E_USER_ERROR);
    }
}
