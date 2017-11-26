<?php

namespace Chicoco;

class Init
{
    public function __construct()
    {
        spl_autoload_register(array($this, "autoLoadClass"));
    }

    public function autoLoadClass($className)
    {
        $className = str_replace("Chicoco\\", '', $className);

        if (is_file("core/".$className.".php")) {
            include_once("core/".$className.".php");
        } elseif (is_file("controller/".$className.".php")) {
            include_once("controller/".$className.".php");
        } elseif (is_file("model/".$className.".php")) {
            include_once("model/".$className.".php");
        } else {
            throw new \Exception("Unable to load class $className.");
        }
    }
}
