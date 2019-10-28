<?php

namespace Chicoco\Http\Exceptions;

use Exception;

class HttpException extends Exception
{
    public function __construct(String $message = null, $code = 0, Throwable $previous = NULL)
    {
        $this->messages = $message;
        parent::__construct($message, $code, $previous);
    }
}
