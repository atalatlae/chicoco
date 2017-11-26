<?php

/*
 * This class is an example to show how chicoco framework use the database clases
 */

class UserDao extends Chicoco\Dao
{
	public function getUserByLogin($user = "") {
		try {
			$this->setSql('SELECT host, user, last_access FROM user WHERE User = :user');
			$this->clearParams();
			$this->addParam(':user', $user, PDO::PARAM_STR);
			$this->doSelect();

			return $this->result;
		}
		catch (Exception $e) {
			$this->msgResult = $e->getMessage();
			return false;
		}
	}

	public function updateLasAccess($user) {
		try {
			$this->begin();
			$this->setSql('UPDATE user SET last_access = NOW() WHERE user = :user');
			$this->clearParams();
			$this->addParam(':user', $user, PDO::PARAM_STR);

			$result = $this->doUpdate();

			if ($result !== true) {
				throw new Exception('Transaction Error');
			}
			$this->commit();
			return true;
		}
		catch (Exceptio $e) {
			$this->rollback();
			$this->msgResult = $e->getMessage();
			return false;
		}
	}
}
