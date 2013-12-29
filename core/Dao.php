<?php

class Dao extends DataBase
{
	private $_db;
	protected $msgResult = "";

	function __construct() {
		$this->_db = Database::getInstance();
		parent::__construct();
	}

	public function getMsgResult() {
		return $this->msgResult;
	}

	public function doSelect($sql = '', Array $params) {
		try {
			$stmt = $this->_db->prepare($sql);

			if (is_array($params) && count($params) > 0 ) {
				foreach ($params as $p) {

					if (!isset($p['type'])) {
						$p['type'] = PDO::PARAM_STR;
					}

					$stmt->bindParam($p['key'], $p['value'], $p['type']);
				}
			}

			$query = $stmt->execute();

			if ($query !== true) {
				$error = $stmt->errorInfo();
				throw new Exception('Dao: '.var_export($error, true));
			}

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		}
		catch (Exception $e) {
			$this->msgResult = $e->getMessage();
			return false;
		}
	}

	public function doInsert($sql, $params) {
		return $this->doUpdate($sql, $params);
	}

	public function doUpdate($sql, $params) {
		try {
			$stmt = $this->_db->prepare($sql);

			if (is_array($params) && count($params) > 0 ) {
				foreach ($params as $p) {

					if (!isset($p['type'])) {
						$p['type'] = PDO::PARAM_STR;
					}

					$stmt->bindParam($p['key'], $p['value'], $p['type']);
				}
			}

			$query = $stmt->execute();

			if ($query !== true) {
				$error = $stmt->errorInfo();
				throw new Exception('Dao: '.var_export($error, true));
			}
			return true;
		}
		catch (Exception $e) {
			$this->msgResult = $e->getMessage();
			return false;
		}
	}
}
