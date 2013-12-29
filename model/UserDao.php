<?php 

/*
 * This class is an example to show how chicoco framework use the database clases
 */

class UserDao extends Dao
{
	public function getUserByLogin($user = "") {
		try {
			$sql = 'SELECT Host, User FROM user WHERE User = :user';

			$params = array(
				array('key' => ':user', 'value' => $user, 'type' => PDO::PARAM_STR)
			);
			
			$result = $this->doSelect($sql, $params);
			return $result;
		}
		catch (Exception $e) {
			$this->msgResult($e->getMessage());
			return false;
		}
	}
}
