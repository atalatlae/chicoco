<?php

namespace Chicoco\Core\Exceptions;

use Exception;

class FileException extends Exception
{
    public function __construct(string $message = null, $code = 0, Throwable $previous = null)
    {
        $this->messages = $message;
        parent::__construct('[File Exception] ' . $message, $code, $previous);
    }
}
