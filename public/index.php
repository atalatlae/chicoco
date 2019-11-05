<?php

chdir(dirname(__DIR__));

include_once('vendor/autoload.php');

use Chicoco\Core\Application;

use Chicoco\Core\Conf;
use Chicoco\Http\Router;
use Chicoco\Http\HttpRequest;
use Chicoco\Http\HttpController;
use Chicoco\DataBase\DataBase;
use Chicoco\DataBase\Dao;
use Chicoco\Core\Session;

/* Session */
$session = Session::getInstance();
$time = $session->getVar('time');

if ($time == null || (time() - $time  > 5)) {
    $session->setVar('time', time());
}
echo $time.'<br>';

/* Request */
$r = new HttpRequest();

/* Controller */
$c1 = new HttpController($r);

/* Router */
$router = new Router($r);
$router->get('/', function() { echo "HW"; } );
$router->get('/Controller', [$c1, 'run']);

/* Application */
$app = new Application($router);
$app->run();

/* Dao and Database */
$db = DataBase::getInstance('root', '', 'mysql');
$dao = new Dao($db);

$dao->setSql('SELECT Host, Db FROM db');
$dao->clearParams();

$r = $dao->doSelect();
$record = $dao->getResult('class', 'stdClass');
echo "<pre>";
print_r($record);
