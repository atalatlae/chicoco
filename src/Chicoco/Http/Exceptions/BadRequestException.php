<?php

namespace Chicoco\Http\Exceptions;

class BadRequestException extends HttpException
{
    public function __construct(string $message = null, $code = 0, Throwable $previous = null)
    {
        $this->messages = $message;
        parent::__construct('Bad Request: ' . $message, $code, $previous);
    }
}
