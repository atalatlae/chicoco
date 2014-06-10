namespace Chicoco;

class Controller
{
	protected _controller;
	protected _action;
	protected _defaultLayout = "default";
	protected _pathParams;
	protected _scripts;
	protected _log;
	protected _data;

	public function __construct() {
		let this->_log = new Log();
	}

	public function __call(name, params) {
		return false;
	}

	public function init() {
	}

	public function setController(controller) {
		let this->_controller = controller;
		return this;
	}

	public function setAction(action) {
		let this->_action = action;
		return this;
	}

	public function setPathParams(params) {
		let this->_pathParams = params;
		return this;
	}

	public function getPathParams() {
		return this->_pathParams;
	}

	public function render(layout = "", fileName = "") {

		var content;
		var viewDir;

		if layout != "" && is_file("layout/".layout.".phtml") {
			let this->_defaultLayout = layout;
		}

		let viewDir = preg_replace("/(.*)Controller$/", "${1}", this->_controller);

		if (fileName != "") {
			if (!is_file("view/".viewDir."/".fileName.".phtml")) {
				let fileName = this->_action;
			}
		}
		else {
			let fileName = this->_action;
		}

		// Put the variables visible to the included file
		if (is_array(this->_data)) {
			var k, v;
			var foo;

			for k, v in this->_data {
				let foo = k;
				let {foo} = v;
			}
		}

		ob_start();
		require("view/".viewDir."/".fileName.".phtml");
		let content = "content";
		let {content} = ob_get_contents();
		ob_end_clean();
		require("layout/".this->_defaultLayout.".phtml");
		exit();
	}

	public function redirect(path = "") {
		if (path != "") {
			header("Location: ".path);
			exit();
		}
	}

	public function setViewVar(name, value) {
		if (name != "") {
			let this->_data[name] = value;
		}
	}

	public function setViewScript(script) {
		if (script != "") {
			let this->_scripts[] = script;
			this->setViewVar("viewScripts", this->_scripts);
		}
	}

	public function logInfo(message = "") {
		return this->_log->info(message, this->_controller, this->_action);
	}

	public function logWarning(message = "") {
		return this->_log->warning(message, this->_controller, this->_action);
	}

	public function logError(message = "") {
		return this->_log->error(message, this->_controller, this->_action);
	}

	public function getUrlVar(name = "", type = "") {
		return this->_getVar(name, type, "get");
	}

	public function getPostVar(name = "", type = "") {
		return this->_getVar(name, type, "post");
	}

	public function getRequestVar(name = "", $type = "") {
		return this->_getVar(name, type, "request");
	}

	public function getFileVar(name = "") {
		return $this->_getVar(name, "", "file");
	}

	protected function _getVar(name = "", type = "", from = 1) {
		switch from {
			case "get":
				if (isset(_GET[name])) {
					return this->saniticeVar(_GET[name], type);
				}
				break;
			case "post":
				if (isset(_POST[name])) {
					return this->saniticeVar(_POST[name], type);
				}
				break;
			case "request":
				if (isset(_REQUEST[name])) {
					return this->saniticeVar(_REQUEST[name], type);
				}
				break;
			case "file":
				if (isset(_FILES[name])) {
					return _FILES[name];
				}
				break;
		}
	}

	protected function saniticeVar(variable = null, type = "string") {
		var filters = [
			"string": FILTER_SANITIZE_STRING,
			"email" : FILTER_SANITIZE_EMAIL,
			"float" : FILTER_SANITIZE_NUMBER_FLOAT,
			"int"   : FILTER_SANITIZE_NUMBER_INT,
			"url"   : FILTER_SANITIZE_URL
		];

		if isset(filters[type]) {
			return filter_var(variable, filters[type]);
		}
		else {
			return variable;
		}
	}

	protected function dumpVar(variable) {
		var v;
		string s;
		let v = var_export(variable, true);

		let s = "<pre>".v."</pre>";
		return s;
	}

}
