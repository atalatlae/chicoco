<?php

namespace Chicoco;

use Chicoco\Log;

class LogDb extends Log
{
    private $db;
    private $levels = array(
        "6" => "LOG_INFO",
        "4" => "LOG_WARNING",
        "3" => "LOG_ERR"
    );

    public function __construct()
    {
        $this->db = new Dao();
    }

    protected function write($message = '', $controller = '', $action = '')
    {
        $level = $this->logLevel;

        try {
            $l = $this->levels["$level"];

            $this->db->setSql('INSERT INTO logs '
            .'(timestamp, level, message, controller, action) VALUES '
            .'(NOW(), :level, :message, :controller, :action)');
            $this->db->clearParams();
            $this->db->addParam(':level', $l, \PDO::PARAM_STR);
            $this->db->addParam(':message', $message, \PDO::PARAM_STR);
            $this->db->addParam(':controller', $controller, \PDO::PARAM_STR);
            $this->db->addParam(':action', $action, \PDO::PARAM_STR);

            $this->db->doInsert();
            $result = $this->db->getResult();

            if ($result === false) {
                $error = $this->db->getMsgResult();
                throw new \Exception('Log: '.var_export($error, true));
            }

            return true;
        } catch (\Exception $e) {
            $this->_msgResult = $e->getMessage();
            return false;
        }
    }
}
