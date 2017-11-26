<?php

namespace Chicoco;

class DataBase extends \PDO
{
	protected static $_instance;

	protected $engine;
	protected $host;
	protected $port;
	protected $dbname;
	protected $username;
	protected $pass;

	public function __construct()
	{
		$app = Application::getInstance();
		$conf = $app->getConfig();

		$this->engine = $conf["database.engine"];
		$this->host = $conf["database.host"];
		$this->port = $conf["database.port"];
		$this->dbname = $conf["database.dbname"];
		$this->username = $conf["database.username"];
		$this->pass = $conf["database.pass"];

		try {
			$dsn = $this->engine.":dbname=".$this->dbname."; host=".$this->host."; port=".$this->port;
			parent::__construct($dsn, $this->username, $this->pass);
		}
		catch (\Exception $e) {
			throw new \Exception('Database: error when try to connect: '.$e->getMessage());
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
