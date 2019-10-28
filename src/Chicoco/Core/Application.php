<?php

namespace Chicoco\Core;

use Exception;
use Chicoco\Core\Interfaces\Handler;

class Application
{
    protected $handler;

    public function __construct(Handler $h)
    {
        $this->handler = $h;
    }

    public function run()
    {
        $this->handler->execute();
    }
}
