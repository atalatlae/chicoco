<?php

namespace Chicoco\DataBase\Exceptions;

class DBIntegrityException extends DaoException
{
    public function __construct(string $message = null, $code = 0, Throwable $previous = null)
    {
        $this->messages = $message;
        parent::__construct('Integrity Exception: ' . $message, $code, $previous);
    }
}
