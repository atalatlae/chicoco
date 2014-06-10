<?php

namespace Chicoco;

chdir(dirname(__DIR__));

require_once("core/Init.php");

$init = new Init();

$app = Application::getInstance();
$app->run();
