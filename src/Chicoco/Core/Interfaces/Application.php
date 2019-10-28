<?php

namespace Chicoco\Core\Interfaces;

use Chicoco\Core\Interfaces\Handler;

interface Application
{
    public function __construct(Handler $h);
    public function run();
}
