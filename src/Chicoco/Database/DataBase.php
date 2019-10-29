<?php

namespace Chicoco\DataBase;

use PDO;
use Exception;
use RuntimeException;

class DataBase extends PDO
{
    protected static $instance;

    private function __construct($user, $pass, $dbname, $host = 'localhost', $port = 3306, $engine = 'mysql', $charset = 'utf8')
    {
        $dsn = sprintf('%s:dbname=%s; host=%s; port=%d; charset=%s', $engine, $dbname, $host, $port, $charset);
        parent::__construct($dsn, $user, $pass, array(PDO::MYSQL_ATTR_LOCAL_INFILE => true));
        self::$instance = $this;
    }

    public static function getInstance($user, $pass, $dbname, $host = 'localhost', $port = 3306, $engine = 'mysql')
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self($user, $pass, $dbname, $host, $port, $engine);
        }
        return self::$instance;
    }
}