<?php

namespace Chicoco\DataBase\Exceptions;

class DBIntegrityException extends DaoException
{
    public function __construct(String $message = null, $code = 0, Throwable $previous = NULL)
    {
        $this->messages = $message;
        parent::__construct('Integrity Exception: '.$message, $code, $previous);
    }
}
