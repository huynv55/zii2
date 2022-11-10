<?php declare(strict_types=1);
$GLOBALS['composer'] = require __DIR__."/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('CONTAINER_NAME', 'container');
define('APPLICATION_NAME', 'app');

require __DIR__."/bootstrap/func.php";

$app = require __DIR__."/bootstrap/app.php";

var_dump($argv);
?>