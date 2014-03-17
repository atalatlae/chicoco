<?php

class Log
{
	public function info($message = '', $controller = '', $action = '') {
		if ($message != '') {
			return $this->_write($message, LOG_INFO, $controller, $action);
		}
	}

	public function warning($message = '', $controller = '', $action = '') {
		if ($message != '') {
			return $this->_write($message, LOG_WARNING, $controller, $action);
		}
	}

	public function error($message = '', $controller = '', $action = '') {
		if ($message != '') {
			return $this->_write($message, LOG_ERR, $controller, $action);
		}
	}

	protected function _write($message = '', $level = LOG_INFO, $controller = '', $action = '') {
		try {
			syslog($level, "$controller/$action: $message");

			return true;
		}
		catch (Exception $e) {
			$this->msgResult = $e->getMessage();
			return false;
		}
	}
}
