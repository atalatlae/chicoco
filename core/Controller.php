<?php

class Controller
{
	protected $_controller;
	protected $_action;
	protected $_defaultLayout = 'default';
	private   $_data = array();
	protected $_pathParams = array();
	protected $_scripts;

	public function __construct() {

	}

	public function __call($name, $params) {
		return false;
	}

	public function init() {
	}

	public function setController($controller) {
		$this->_controller = $controller;
		return $this;
	}

	public function setAction($action) {
		$this->_action = $action;
		return $this;
	}

	public function setPathParams(array $params) {
		$this->_pathParams = $params;
		return $this;
	}

	public function getPathParams() {
		return $this->_pathParams;
	}

	public function render($layout = '', $fileName = '') {

		if ($layout != '' && is_file('layout/'.$layout.'.phtml')) {
			$this->_defaultLayout = $layout;
		}

		$viewDir = preg_replace('/(.*)Controller$/', '${1}', $this->_controller);

		if ($fileName != '') {
			if (!is_file('view/'.$viewDir.'/'.$fileName.'.phtml')) {
				$fileName = $this->_action;
			}
		}
		else {
			$fileName = $this->_action;
		}

		// Put the variables visible to the included file
		if (is_array($this->_data)) {
			foreach($this->_data as $k => $v) {
				${$k}  = $v;
			}
		}

		ob_start();
		include('view/'.$viewDir.'/'.$fileName.'.phtml');
		$content = ob_get_contents();
		ob_end_clean();

		include('layout/'.$this->_defaultLayout.'.phtml');
		exit();
	}

	public function redirect($path = '') {
		if ($path != '') {
			header('Location: '.$path);
			exit();
		}
	}

	public function setViewVar($name, $value) {
		if ($name != '') {
			$this->_data[$name] = $value;
		}
	}

	public function setViewScript($script) {
		if ($script != '') {
			$this->_scripts[] = $script;
			$this->setViewVar('viewScripts', $this->_scripts);
		}
	}

	public function logInfo($message = '') {
		$this->_log->info($message, $this->_controller, $this->_action);
	}

	public function logWarning($message = '') {
		$this->_log->warning($message, $this->_controller, $this->_action);
	}

	public function logError($message = '') {
		$this->_log->error($message, $this->_controller, $this->_action);
	}

	public function getUrlVar($name = '', $type = '') {
		return $this->_getVar($name, $type, 'get');
	}

	public function getPostVar($name = '', $type = '') {
		return $this->_getVar($name, $type, 'post');
	}

	public function getRequestVar($name = '', $type = '') {
		return $this->_getVar($name, $type, 'request');
	}

	public function getFileVar($name = '') {
		return $this->_getVar($name, '', 'file');
	}

	private function _getVar($name = '', $type = '', $from = 'get') {
		switch ($from) {
			case 'get':
				if (isset($_GET[$name])) {
					return $this->saniticeVar($_GET[$name], $type);
				}
				break;
			case 'post':
				if (isset($_POST[$name])) {
					return $this->saniticeVar($_POST[$name], $type);
				}
				break;
			case 'request':
				if (isset($_REQUEST[$name])) {
					return $this->saniticeVar($_REQUEST[$name], $type);
				}
				break;
			case 'file':
				if (isset($_FILES[$name])) {
					return $_FILES[$name];
				}
				break;
			default:
				return null;
				break;
		}
		return null;
	}

	private function saniticeVar($var = null, $type = "")
	{
		$filters = array(
			"string" => FILTER_SANITIZE_STRING,
			"email"  => FILTER_SANITIZE_EMAIL,
			"float"  => FILTER_SANITIZE_NUMBER_FLOAT,
			"int"    => FILTER_SANITIZE_NUMBER_INT,
			"url"    => FILTER_SANITIZE_URL
		);

		if (isset($filters[$type])) {
			return filter_var($var, $filters[$type]);
		}
		else {
			return $var;
		}
	}

	protected function dumpVar($var) {
		$s = '<pre>'
		.var_export($var, true)
		.'</pre>';
		return $s;
	}
}
