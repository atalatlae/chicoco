<?php

namespace Chicoco\DataBase\Exceptions;

class DuplicatedEntityException extends DaoException
{
    public function __construct(string $message = null, $code = 0, Throwable $previous = null)
    {
        $this->messages = $message;
        parent::__construct('Duplicated Entity Exception: ' . $message, $code, $previous);
    }
}
