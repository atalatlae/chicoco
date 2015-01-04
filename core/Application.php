<?php

namespace Chicoco;

class Application
{
	private static $_instance;

	private $_controller = '';
	private $_action = '';
	private $_uriParts = '';
	private $_path ='';
	private $_config;
	private $_pathParams = array();

	public function __construct() {
		try {
			$this->loadConfig();

			$this->_uriParts = parse_url($_SERVER['REQUEST_URI']);
			$this->_path = explode('/', $this->_uriParts['path']);

			if (isset($this->_path[1]) && $this->_path[1] != '') {
				$this->_controller = $this->_path[1];
			}
			else {
				$this->_controller = 'Index';
			}

			if (isset($this->_path[2]) && $this->_path[2] != '') {
				$this->_action = $this->_path[2];
			}
			else {
				$this->_action = 'Index';
			}

			if (count($this->_path) > 3) {
				$c = count($this->_path);
				for ($i=3; $i < $c ; $i++) {
					$this->_pathParams[] = $this->_path[$i];
				}
			}
		}
		catch(\Exception $e) {
			header("HTTP/1.0 500 Error found");
			include('layout/500.phtml');
			exit();
		}
	}

	public static function getInstance() {
		if (  !self::$_instance instanceof self) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	private function _checkAlias() {
		if (isset($this->_config['Aliases'])) {
			$aliases = $this->_config['Aliases'];
			$key = $this->_uriParts['path'];

			$this->_addToGlobal('ALIAS', '');

			if (isset($aliases[$key])) {
				$this->_addToGlobal('ALIAS', $key);
				list($this->_controller, $this->_action) = explode("/", $aliases[$key]);
			}
		}
	}

	public function run() {
		try {
			$this->_checkAlias();

			$controller = $this->_controller.'Controller';
			$c = new $controller;

			if (!($c instanceof Controller)) {
				throw new \Exception('Unable to load class '.$this->_controller);
			}

			$c->setController($this->_controller);
			$c->setAction($this->_action);
			$c->setPathParams($this->_pathParams);
			$c->init();

			if (!method_exists($c, $this->_action.'Action')) {
				throw new \Exception('Unable to execute the action "'.$this->_action.'"');
			}

			$this->_addToGlobal('CONTROLLER', $this->_controller);
			$this->_addToGlobal('ACTION', $this->_action);
			$this->_addToGlobal('PATH_PARAMS', $this->_pathParams);

			$c->{$this->_action.'Action'}();
		}
		catch (\Exception $e) {
			header("HTTP/1.0 500 Error found");
			include('layout/500.phtml');
			return false;
		}
	}

	private function loadConfig() {
		$this->_config = array();
		if (!($config = @parse_ini_file('conf/Application.ini', true))) {
			throw new \Exception("unable to load configuration file");
		}

		if (is_array($config)) {
			// Get the general conf
			if (is_array($config['General'])) {
				foreach ($config['General'] as $k => $v) {
					$this->_config[$k] = $v;
				}
			}

			// Get the conf for the current env
			if (isset($this->_config['applicaction.env'])
				&& is_array($config[$this->_config['applicaction.env']])) {
				foreach ($config[$this->_config['applicaction.env']] as $k => $v) {
					$this->_config[$k] = $v;
				}
			}

			// Get the common conf
			if (isset($config['Common']) && is_array($config['Common'])) {
				foreach ($config['Common'] as $k => $v) {
					$this->_config[$k] = $v;
				}
			}

			// Get the aliases conf
			if (isset($config['Aliases']) && is_array($config['Aliases'])) {
				$this->_config['Aliases'] = array();
				foreach ($config['Aliases'] as $k => $v) {
					$this->_config['Aliases'][$k] = $v;
				}
			}
			$this->_addToGlobal('CONF', $this->_config);
		}
	}

	public function getConfig() {
		return $this->_config;
	}

	private function _addToGlobal($key, $value) {
		global $_CHICOCO;

		if (!isset($_CHICOCO)) {
			$_CHICOCO = array();
		}

		$_CHICOCO[$key] = $value;
	}
}
