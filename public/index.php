<?php
require __DIR__."/../vendor/autoload.php";

define('CONTAINER_NAME', 'container');
define('APPLICATION_NAME', 'app');
$app = require __DIR__."/../bootstrap/app.php";

require __DIR__."/../bootstrap/func.php";
$app->run();
?>