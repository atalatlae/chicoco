<?php

namespace Chicoco\Http\Exceptions;

class BadRequestException extends HttpException
{
    public function __construct(String $message = null, $code = 0, Throwable $previous = NULL)
    {
        $this->messages = $message;
        parent::__construct('Bad Request: '.$message, $code, $previous);
    }
}

