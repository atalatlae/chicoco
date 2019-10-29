<?php

namespace Chicoco;

class Singleton
{
    protected static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        $calledClass = get_called_class();

        if (!self::$instance instanceof $calledClass) {
            self::$instance = new $calledClass();
        }
        return self::$instance;
    }

    public function __clone()
    {
        trigger_error('Clone is not allowed', E_USER_ERROR);
    }
}
