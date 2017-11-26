<?php

namespace Chicoco;

class LogDb extends Log
{
    private $_db;
    private $_levels = array(
        "6" => "LOG_INFO",
        "4" => "LOG_WARNING",
        "3" => "LOG_ERR"
    );

    public function __construct() {
        $this->_db = new Dao();
    }

    protected function _write($message = '', $controller = '', $action = '') {
        $level = $this->_logLevel;

        try {
            $l = $this->_levels["$level"];

            $this->_db->setSql('INSERT INTO logs '
            .'(timestamp, level, message, controller, action) VALUES '
            .'(NOW(), :level, :message, :controller, :action)');
            $this->_db->clearParams();
            $this->_db->addParam(':level',      $l,         \PDO::PARAM_STR);
            $this->_db->addParam(':message',    $message,   \PDO::PARAM_STR);
            $this->_db->addParam(':controller', $controller,\PDO::PARAM_STR);
            $this->_db->addParam(':action',     $action,    \PDO::PARAM_STR);

            $this->_db->doInsert();
            $query = $this->_db->getResult();

            if ($query === false) {
                $error = $this->_db->getMsgResult();
                throw new \Exception('Log: '.var_export($error, true));
            }

            return true;
        }
        catch (\Exception $e) {
            $this->_msgResult = $e->getMessage();
            return false;
        }
    }
}
