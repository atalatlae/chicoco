<?php

chdir(dirname(__DIR__));

include_once('vendor/autoload.php');

use Chicoco\Core\Application;

use Chicoco\Core\Conf;
use Chicoco\Http\Router;
use Chicoco\Http\HttpRequest;
use Chicoco\Http\HttpController;

/* Request */
$r = new HttpRequest();

/* Controller */
$c1 = new HttpController($r);

/* Router */
$router = new Router($r);
$router->get('/', function() { echo "HW"; } );
$router->get('/Controller', [$c1, 'run']);

$app = new Application($router);
$app->run();

