<?php

namespace Chicoco\Http\Exceptions;

class NotImplementedException extends HttpException
{
    public function __construct(String $message, $code = 0, Throwable $previous = NULL)
    {
        $this->messages = $message;
        parent::__construct('Not Implemented: '.$message, $code, $previous);
    }
}
