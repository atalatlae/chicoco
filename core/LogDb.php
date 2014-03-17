<?php

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

	protected function _write($message = '', $level = LOG_INFO, $controller = '', $action = '') {
		try {
			$sql = 'INSERT INTO logs '
			.'(timestamp, level, message, controller, action) VALUES '
			.'(NOW(), :level, :message, :controller, :action)';

			$l = $this->_levels["$level"];

			$params = array(
				array('key' => ':level',      'value' => $l,          'type' => PDO::PARAM_STR),
				array('key' => ':message',    'value' => $message,    'type' => PDO::PARAM_STR),
				array('key' => ':controller', 'value' => $controller, 'type' => PDO::PARAM_STR),
				array('key' => ':action',     'value' => $action,     'type' => PDO::PARAM_STR),
			);

			$query = $this->_db->doSelect($sql, $params);

			if ($query === false) {
				$error = $stmt->errorInfo();
				throw new Exception('Log: '.var_export($error, true));
			}

			return true;
		}
		catch (Exception $e) {
			$this->msgResult = $e->getMessage();
			return false;
		}
	}
}
