namespace Chicoco;

class Log
{
	protected _logLevel;

	public function info(message = "", controller = "", action = "") {
		if (message != "") {
			let this->_logLevel = LOG_INFO;
			return this->_write(message, controller, action);
		}
	}

	public function warning(message = "", controller = "", action = "") {
		if (message != "") {
			let this->_logLevel = LOG_WARNING;
			return this->_write(message, controller, action);
		}
	}

	public function error(message = "", controller = "", action = "") {
		if (message != "") {
			let this->_logLevel = LOG_ERR;
			return this->_write(message, controller, action);
		}
	}

	protected function _write(message = "", controller = "", action = "") {
		syslog(this->_logLevel, controller."/".action.": ".message);
		return true;
	}
}
