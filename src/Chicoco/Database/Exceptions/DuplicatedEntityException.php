<?php

namespace Chicoco\DataBase\Exceptions;

class DuplicatedEntityException extends DaoException
{
    public function __construct(String $message = null, $code = 0, Throwable $previous = NULL)
    {
        $this->messages = $message;
        parent::__construct('Duplicated Entity Exception: '.$message, $code, $previous);
    }
}
