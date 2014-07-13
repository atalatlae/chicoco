<?php 

/*
 * This class is an example to show how chicoco framework use the database clases
 */

class UserDao extends Chicoco\Dao
{
	public function getUserByLogin($user = "") {
		try {
			$this->setSql('SELECT Host, User FROM user WHERE User = :user');
			$this->clearParams();
			$this->addParam(':user', $user, PDO::PARAM_STR);
			$this->doSelect();

			return $this->_result;
		}
		catch (Exception $e) {
			$this->msgResult = $e->getMessage();
			return false;
		}
	}
}
