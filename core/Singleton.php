<?php

class Singleton
{
	protected static $_instance;

	public function __construct(){
	}

	public static function getInstance() {
		$calledClass = get_called_class();

		if (  !self::$_instance instanceof $calledClass) {
			self::$_instance = new $calledClass;
		}
		return self::$_instance;
	}

	public function __clone() {
		trigger_error('Clone is not allowed', E_USER_ERROR);
	}
}
