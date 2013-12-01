<?php

class ExampleController extends Controller
{
	public function init() {
	}

	public function IndexAction() {
		$this->render();
	}

	public function FormAction() {
		
		$send = $this->getPostVar('send', 'string');

		if (isset($send) and $send != '') {
			$name = $this->getPostVar('name', 'string');

			if (isset($name) and $name != '') {
				$formResult = 'Form OK, you text is: '.$name;
			}
			else {
				$formResult = 'Please, write some text';
			}
			$this->setViewVar('formResult', $formResult);
		}
		$this->render();
	}

	public function DBAccessAction() {
		$sql = 'SELECT Host, User FROM user WHERE User = :user';
		$params = array(
			array('key' => ':user', 'value' => 'root', 'type' => PDO::PARAM_STR)
		);
		
		$dao = new Dao();
		$result = $dao->doSelect($sql, $params);

		$this->setViewVar('result', $result);

		$this->render();
	}
}
