<?php

namespace Chicoco\Http\Exceptions;

class InternalErrorException extends HttpException
{
    public function __construct(String $message = null, $code = 0, Throwable $previous = NULL)
    {
        $this->messages = $message;
        parent::__construct('Internal Exception: '.$message, $code, $previous);
    }
}
