<?php

namespace Chicoco;

class Log
{
	protected $_logLevel = LOG_INFO;
	protected $_msgResult;

	public function info($message = '', $controller = '', $action = '') {
		if ($message != '') {
			$this->_logLevel = LOG_INFO;
			return $this->_write($message, $controller, $action);
		}
	}

	public function warning($message = '', $controller = '', $action = '') {
		if ($message != '') {
			$this->_logLevel = LOG_WARNING;
			return $this->_write($message, $controller, $action);
		}
	}

	public function error($message = '', $controller = '', $action = '') {
		if ($message != '') {
			$this->_logLevel = LOG_ERR;
			return $this->_write($message, $controller, $action);
		}
	}

	public function getMsgResult() {
		return $this->_msgResult;
	}

	protected function _write($message = '', $controller = '', $action = '') {
		try {
			syslog($this->_logLevel, "$controller/$action: $message");
			return true;
		}
		catch (\Exception $e) {
			$this->_msgResult = $e->getMessage();
			return false;
		}
	}
}
