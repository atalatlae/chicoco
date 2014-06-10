namespace Chicoco;

class Dao extends DataBase
{
	protected _db;
	protected msgResult = "";

	public function __construct() {
		let this->_db = this->getInstance();
	}

	public function getMsgResult() {
		return this->msgResult;
	}

	public function doSelect(sql = "", params) {
//		try {
			var stmt, query, result;


			let stmt = this->_db->prepare(sql);

			if is_array(params) && count(params) > 0 {
				var p;

				for p in params {

					if !isset(p["type"]) {
						let p["type"] = \PDO::PARAM_STR;
					}
					stmt->bindParam(p["key"], "".p["value"], p["type"]);
				}
			}

			let query = stmt->execute();

			if (query !== true) {
				return false;
//				$error = $stmt->errorInfo();
//				throw new Exception('Dao: '.var_export($error, true));
			}

			let result = stmt->fetchAll(\PDO::FETCH_ASSOC);

			return result;
//		}
//		catch (Exception $e) {
//			$this->msgResult = $e->getMessage();
//			return false;
//		}
	}

	public function doInsert(sql, params) {
		return this->doUpdate(sql, params);
	}

	public function doUpdate(sql, params) {
		// try {
			var stmt, query;

			let stmt = this->_db->prepare(sql);

			if (is_array(params) && count(params) > 0 ) {
				var p;

				for p in params {
					if (!isset(p["type"])) {
						let p["type"] = \PDO::PARAM_STR;
					}
					stmt->bindParam(p["key"], p["value"], p["type"]);
				}
			}

			let query = stmt->execute();

			if (query !== true) {
				//$error = $stmt->errorInfo();
				//throw new Exception('Dao: '.var_export($error, true));
				return false;
			}
			return true;
		//}
		//catch (Exception $e) {
		//	$this->msgResult = $e->getMessage();
		//	return false;
		//}
	}
}
