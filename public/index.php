<?php

chdir(dirname(__DIR__));

require_once("core/Init.php");

$init = new Init();

$app = Application::getInstance();
$conf = $app->getConfig();
$app->run();
