<?php

namespace Chicoco\Http\Exceptions;

class NotImplementedException extends HttpException
{
    public function __construct(string $message, $code = 0, Throwable $previous = null)
    {
        $this->messages = $message;
        parent::__construct('Not Implemented: ' . $message, $code, $previous);
    }
}
