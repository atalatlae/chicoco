<?php

namespace Chicoco\Http\Exceptions;

class NotFoundException extends HttpException
{
    public function __construct(String $message = null, $code = 0, Throwable $previous = NULL)
    {
        $this->messages = $message;
        parent::__construct('Not found: '.$message, $code, $previous);
    }
}
