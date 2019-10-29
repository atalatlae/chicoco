<?php

namespace Chicoco\Http\Exceptions;

class NotFoundException extends HttpException
{
    public function __construct(string $message = null, $code = 0, Throwable $previous = null)
    {
        $this->messages = $message;
        parent::__construct('Not found: ' . $message, $code, $previous);
    }
}
