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
		$this->loadConfig();

		$this->_uriParts = parse_url($_SERVER['REQUEST_URI']);
		$this->_path = explode('/', $this->_uriParts['path']);

		if (isset($this->_path[1]) && $this->_path[1] != '') {
			$this->_controller = $this->_path[1].'Controller';
		}
		else {
			$this->_controller = 'IndexController';
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

	public static function getInstance() {
		if (  !self::$_instance instanceof self) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	public function run() {
		try {
			$c = new $this->_controller;

			if ( !($c instanceof Controller)) {
				throw new \Exception('Unable to load class '.$this->_controller);
			}

			$c->setController($this->_controller);
			$c->setAction($this->_action);
			$c->setPathParams($this->_pathParams);

			$c->init();
			$c->{$this->_action.'Action'}();
		}
		catch (\Exception $e) {
			header("HTTP/1.0 404 Not Found");
			include('layout/404.phtml');
			return false;
		}
	}

	private function loadConfig() {
		$this->_config = array();
		$config = parse_ini_file('conf/Application.ini', true);

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

		}
	}

	public function getConfig() {
		return $this->_config;
	}

	private function _sanitizeUriPart() {
	}
}
