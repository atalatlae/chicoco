<?php

namespace Chicoco\Core;

class Session
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        self::$instance->initSession();
        return self::$instance;
    }

    private static function initSession()
    {
        if (session_status() === 1) {
            session_start();
        }
    }

    public function __get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }

    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }

    public function getSessionId()
    {
        return session_id();
    }

    public function destroy()
    {
        session_unset();
        session_destroy();
    }
}
