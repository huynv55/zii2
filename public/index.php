<?php declare(strict_types=1);
//require __DIR__.'/basic_auth.php';
$GLOBALS['composer'] = require __DIR__."/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

define('CONTAINER_NAME', 'container');
define('APPLICATION_NAME', 'app');
define('ROOT_PATH', dirname(__DIR__));

require __DIR__."/../bootstrap/func.php";

$app = require __DIR__."/../bootstrap/app.php";

$app->run();
?>