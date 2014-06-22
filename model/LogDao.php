<?php

/*
 * This class is an example to show how chicoco framework use the database clases
 */

class LogDao extends Chicoco\Dao
{
	public function getLogs() {
		try {
			$this->setSql('SELECT * FROM logs ORDER BY timestamp asc');
			$this->clearParams();
			$this->doSelect();
			$result = $this->getResult();

			return $result;
		}
		catch (Exception $e) {
			$this->msgResult = $e->getMessage();
			return false;
		}
	}
}
