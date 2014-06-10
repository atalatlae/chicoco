namespace Chicoco;

class Application
{
	static  _instance;
	private _controller = "IndexController";
	private _action;
	private _uriParts;
	private _path;
	private _config;
	private _pathParams;

	public function __construct() {
		this->loadConfig();

		let this->_uriParts = parse_url(_SERVER["REQUEST_URI"]);
		let this->_path = explode("/", this->_uriParts["path"]);

		if isset(this->_path[1]) && this->_path[1] != "" {
			let this->_controller = this->_path[1]."Controller";
		}
		else {
			let this->_controller = "IndexController";
		}

		if isset(this->_path[2]) && this->_path[2] != "" {
			let this->_action = this->_path[2];
		}
		else {
			let this->_action = "Index";
		}

		if count(this->_path) > 3 {
			int c, i, j;
			let c = count(this->_path) - 1;

			for i in range(3, c) {
				let j = i - 3;
				let this->_pathParams[j] = this->_path[i];
			}
		}
	}

	public function run() {
		var c;
		var cc;
		var ac;

		let cc = this->_controller;
		let c = new {cc};

		if (!(c instanceof Controller)) {
			// throw new Exception('Unable to load class '.$this->_controller);
			echo "ERROR! the class is not a Controller\n";
			exit();
		}
		c->setController(this->_controller);
		c->setAction(this->_action);
		c->setPathParams(this->_pathParams);
		c->init();

		let ac = this->_action."Action";

		c->{ac}();
	}

	public static function getInstance() {
		if !is_a(self::_instance, "Application") {
			let self::_instance = new Application();
		}
		return self::_instance;
	}

	private function loadConfig() {
		var config, c, k, v;

		let c = [];

		let config = parse_ini_file("conf/Application.ini", true);

		if (is_array(config)) {
			// Get the general conf
			if (is_array(config["General"])) {
				for k, v in config["General"] {
					let c[k] = v;
				}
			}

			// Get the conf for the current env
			if (isset(this->_config["applicaction.env"])
				&& is_array(config[this->_config["applicaction.env"]])) {
				for k, v in config[this->_config["applicaction.env"]] {
					let c[k] = v;
				}
			}

			// Get the common conf
			if (isset(config["Common"]) && is_array(config["Common"])) {
				for k, v in config["Common"] {
					let c[k] = v;
				}
			}
		}
		let this->_config = c;
	}

	public function getConfig() -> array {
		return this->_config;
	}
}
