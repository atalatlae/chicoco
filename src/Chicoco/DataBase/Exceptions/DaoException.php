<?php

namespace Chicoco\DataBase\Exceptions;

use Exception;

class DaoException extends Exception
{
    public function __construct(string $message = null, $code = 0, Throwable $previous = null)
    {
        $this->messages = $message;
        parent::__construct($message, $code, $previous);
    }
}
