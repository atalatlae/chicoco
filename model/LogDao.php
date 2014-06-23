<?php

/*
 * This class is an example to show how chicoco framework use the database clases
 */

class LogDao extends Chicoco\Dao
{
	public function getLogs() {
		try {
			$sql = 'SELECT * FROM logs ORDER BY timestamp asc';
			$result = $this->doSelect($sql, array());

			return $result;
		}
		catch (Exception $e) {
			$this->msgResult = $e->getMessage();
			return false;
		}
	}
}
