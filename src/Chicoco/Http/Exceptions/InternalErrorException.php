<?php

namespace Chicoco\Http\Exceptions;

class InternalErrorException extends HttpException
{
    public function __construct(string $message = null, $code = 0, Throwable $previous = null)
    {
        $this->messages = $message;
        parent::__construct('Internal Exception: ' . $message, $code, $previous);
    }
}
