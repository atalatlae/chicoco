<?php

namespace Chicoco\Core;

class Session extends Singleton
{
    protected function __construct()
    {
        session_start();
    }

    public function setVar($name, $value)
    {
        if (!empty($name)) {
            $_SESSION[$name] = $value;
        }
    }

    public function getVar($name)
    {
        if (!empty($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return null;
    }

    public function getSessionId()
    {
        return session_id();
    }

    public function destroy()
    {
        unset($_SESSION);
        session_destroy();
    }
}
