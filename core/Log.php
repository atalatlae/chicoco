<?php

class Log extends Dao
{
	public function __construct() {
		parent::__construct();
	}

	public function info($message = '', $controller = '', $action = '') {
		if ($message != '') {
			return $this->_write($message, 'INFO', $controller, $action);
		}
	}

	public function warning($message = '', $controller = '', $action = '') {
		if ($message != '') {
			return $this->_write($message, 'WARNING', $controller, $action);
		}
	}

	public function error($message = '', $controller = '', $action = '') {
		if ($message != '') {
			return $this->_write($message, 'ERROR', $controller, $action);
		}
	}

	private function _write($message = '', $level = 'INFO', $controller = '', $action = '') {
		try {
			$sql = 'INSERT INTO logs '
			.'(timestamp, level, message, controller, action) VALUES '
			.'(NOW(), :level, :message, :controller, :action)';

			$params = array(
				array('key' => ':level',      'value' => $level,      'type' => PDO::PARAM_STR),
				array('key' => ':message',    'value' => $message,    'type' => PDO::PARAM_STR),
				array('key' => ':controller', 'value' => $controller, 'type' => PDO::PARAM_STR),
				array('key' => ':action',     'value' => $action,     'type' => PDO::PARAM_STR),
			);
			
			$query = $this->doSelect($sql, $params);

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
