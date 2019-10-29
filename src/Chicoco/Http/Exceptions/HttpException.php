<?php

namespace Chicoco\Http\Exceptions;

use Exception;

class HttpException extends Exception
{
    public function __construct(string $message = null, $code = 0, Throwable $previous = null)
    {
        $this->messages = $message;
        parent::__construct($message, $code, $previous);
    }
}
