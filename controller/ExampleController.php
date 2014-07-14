<?php

class ExampleController extends Chicoco\Controller
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

	public function SendEmailAction() {
		$from = 'noreply@example.com';
		$to = 'example@example.com';
		$subject = 'test email';
		$content = '';

		$data = array(
			'mailContent' => 'Hello World',
			'date' => date('Y-m-d')
		);

		$mail = new Chicoco\Mail($from, $to, $subject, $content, $data);
		$result = $mail->sendHtml();

		if ($result === true) {
			$this->setViewVar('message', 'The email was sent successfully');
		}
		else {
			$this->setViewVar('message', 'The email was not sent');
		}

		$this->render();
	}

	public function LoggingAction() {
		// Overwrite the _log attribure
		$this->_log = new Chicoco\LogDb();

		$this->logInfo("This is a info log");
		$this->logWarning("This is a warning log message");
		$this->logError("This is a error log message");

		$logDao = new LogDao();
		$logs = $logDao->getLogs();

		$this->setViewVar('logs', $logs);

		$this->render();
	}

	public function SessionAction() {
		$session = Chicoco\Session::getInstance();

		if (!$session->getVar('foo')) {
			$session->setVar('foo', rand (1111, 9999 ));
		}

		$changeValue = $this->getRequestVar('change', 'string');
		if (isset($changeValue)) {
			$session->setVar('foo', rand (1111, 9999 ));
			$this->redirect('/Example/Session');
		}

		$foo = $session->getVar('foo');
		$this->setViewVar('foo', $foo);

		$this->render();
	}

	public function UrlParamsAction() {
		$foo = $this->getPathParams('foo');
		$this->setViewVar('foo', $foo);
		$this->render();
	}

	public function AliasAction() {
		$this->render();
	}
}
