namespace Chicoco;

class Singleton
{
	protected static _instance;

	private function __construct(){
	}

	public static function getInstance() {
		var calledClass;
		let calledClass = get_called_class();

		if !is_a(self::_instance, calledClass) {
			let self::_instance = new {calledClass}();
		}
		return self::_instance;
	}
}
