namespace Chicoco;

class DataBase extends \PDO
{
	static _instance = "";
	protected _engine;
	protected _host;
	protected _dbname;
	protected _username;
	protected _pass;

	public function __construct()
	{
		var app, conf, dsn;

		let app = Application::getInstance();
		let conf = app->getConfig();

		let this->_engine = $conf["database.engine"];
		let this->_host = $conf["database.host"];
		let this->_dbname = $conf["database.dbname"];
		let this->_username = $conf["database.username"];
		let this->_pass = $conf["database.pass"];

//		try {
			let dsn = this->_engine.":dbname=".$this->_dbname."; host=".$this->_host;
			parent::__construct(dsn, this->_username, this->_pass);
//		}
//		catch (Exception $e) {
//			throw new Exception('Database: error when try to connect: '.$e->getMessage());
//			return false;
//		}
	}

	public static function getInstance() {
		if !is_a(self::_instance, "DataBase") {
			let self::_instance = new DataBase();
		}
		return self::_instance;
	}
}
