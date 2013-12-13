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
		$userDao = new UserDao();
		$result = $userDao->getUserByLogin('root');

		$this->setViewVar('result', $result);
		$this->render();
	}
}
