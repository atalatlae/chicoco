<?php

namespace Chicoco;

class _DataBase extends \PDO
{
	protected static $_instance;

	protected $engine;
	protected $host;
	protected $dbname;
	protected $username;
	protected $pass;

	public function __construct()
	{
		$app = Application::getInstance();
		$conf = $app->getConfig();

		$this->engine = $conf["database.engine"];
		$this->host = $conf["database.host"];
		$this->dbname = $conf["database.dbname"];
		$this->username = $conf["database.username"];
		$this->pass = $conf["database.pass"];

		try {
			$dsn = $this->engine.":dbname=".$this->dbname."; host=".$this->host;
			parent::__construct($dsn, $this->username, $this->pass);
		}
		catch (Exception $e) {
			throw new Exception('Database: error when try to connect: '.$e->getMessage());
			return false;
		}
	}

	public static function getInstance() {
		if (  !self::$_instance instanceof self) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
}
