<?php

use Chicoco\Controller;
use Chicoco\LogDb;
use Chicoco\Mail;
use Chicoco\Session;
use Chicoco\ViewHTML;

class ExampleController extends Controller
{
    public function init()
    {
    }

    public function IndexAction()
    {
        $view = new ViewHTML();
        $view->setTemplate('Example/Index');
        $view->setViewVar('foo', 'var');
        $view->render();
    }

    public function FormAction()
    {
        $send = $this->getPostVar('send', 'string');

        if (isset($send) and $send != '') {
            $name = $this->getPostVar('name', 'string');

            if (isset($name) and $name != '') {
                $formResult = 'Form OK, you text is: '.$name;
            } else {
                $formResult = 'Please, write some text';
            }
            $this->setViewVar('formResult', $formResult);
        }
        $this->render();
    }

    public function DBAccessAction()
    {
        $userDao = new UserDao();
        $result = $userDao->getUserByLogin('root');

        $this->setViewVar('result', $result);
        $this->render();
    }

    public function DBTransactionAction()
    {
        $userDao = new UserDao();
        $userDao->updateLasAccess('user');

        $result = $userDao->getUserByLogin('user');
        $this->setViewVar('result', $result);

        $this->render();
    }

    public function SendEmailAction()
    {
        $from = 'noreply@example.com';
        $to = 'example@example.com';
        $subject = 'test email';
        $content = '';

        $data = array(
            'mailContent' => 'Hello World',
            'date' => date('Y-m-d')
        );

        $mail = new Mail($from, $to, $subject, $content, $data);
        $result = $mail->sendHtml();

        if ($result === true) {
            $this->setViewVar('message', 'The email was sent successfully');
        } else {
            $this->setViewVar('message', 'The email was not sent');
        }

        $this->render();
    }

    public function LoggingAction()
    {
        // Overwrite the _log attribure
        $this->log = new LogDb();

        $r = $this->logInfo("This is a info log");
        $this->logWarning("This is a warning log message");
        $this->logError("This is a error log message");

        $logDao = new LogDao();
        $logs = $logDao->getLogs();

        $this->setViewVar('logs', $logs);

        $this->render();
    }

    public function SessionAction()
    {
        $session = Session::getInstance();

        if (!$session->getVar('foo')) {
            $session->setVar('foo', rand(1111, 9999));
        }

        $changeValue = $this->getRequestVar('change', 'string');
        if (isset($changeValue)) {
            $session->setVar('foo', rand(1111, 9999));
            $this->redirect('/Example/Session');
        }

        $foo = $session->getVar('foo');
        $this->setViewVar('foo', $foo);

        $this->render();
    }

    public function UrlParamsAction()
    {
        $foo = $this->getPathParams('foo');
        $this->setViewVar('foo', $foo);
        $this->render();
    }

    public function AliasAction()
    {
        $this->render();
    }

    public function PathParamsAction()
    {
        $pathParams = $this->getPathParams();
        $this->setViewVar('pathParams', $pathParams);
        $this->render();
    }

    public function ChicocoGlobalAction()
    {
        global $_CHICOCO;
        $this->setViewVar('_CHICOCO', $_CHICOCO);
        $this->render();
    }
}
