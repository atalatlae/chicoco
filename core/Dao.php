<?php

namespace Chicoco;

class Dao extends DataBase
{
	protected $_db;
	protected $msgResult = "";
	protected $_sql;
	protected $_params;
	protected $_result;

	function __construct() {
		$this->_db = $this->getInstance();
	}

	public function setSql($sql) {
		$this->_sql = $sql;
	}

	public function getResult() {
		return $this->_result;
	}

	public function getMsgResult() {
		return $this->msgResult;
	}

	public function doSelect() {
		try {
			$stmt = $this->_db->prepare($this->_sql);
			$this->_setParams($stmt);
			$query = $stmt->execute();

			if ($query !== true) {
				$error = $stmt->errorInfo();
				throw new \Exception('Dao: '.var_export($error, true));
			}

			$this->_result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		catch (\Exception $e) {
			$this->msgResult = $e->getMessage();
			$this->_result = false;
		}
	}

	public function doInsert() {
		return $this->_doWrite();
	}

	public function doUpdate() {
		return $this->_doWrite();
	}

	public function addParam($key = '', $value = '', $type = PDO::PARAM_STR) {
		// TODO: Fix here when the value == 0 and 0 is a valid value !!!
		// if ($key != '' && $value != '' && $type != '')
		{
			$this->_params[] = array(
				'key'   => $key,
				'value' => $value,
				'type'  => $type
			);
			return true;
		}
		return false;
	}

	public function getParams() {
		return $this->_params;
	}

	public function clearParams() {
		$this->_params = array();
		return true;
	}

	/*** ***/

	protected function _setParams($stmt) {
		if (is_array($this->_params) && count($this->_params) > 0 ) {
			foreach ($this->_params as $p) {

				if (!isset($p['type'])) {
					$p['type'] = \PDO::PARAM_STR;
				}

				$stmt->bindParam($p['key'], $p['value'], $p['type']);
			}
		}
	}

	private function _doWrite() {
		try {
			$stmt = $this->_db->prepare($this->_sql);
			$this->_setParams($stmt);
			$query = $stmt->execute();

			if ($query !== true) {
				$error = $stmt->errorInfo();
				throw new \Exception('Dao: '.var_export($error, true));
			}
			return true;
		}
		catch (\Exception $e) {
			$this->msgResult = $e->getMessage();
			return false;
		}
	}
}
