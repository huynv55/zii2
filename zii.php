<?php
set_time_limit(600);
ini_set("memory_limit", -1);

define("ROOT_PATH", realpath(__DIR__.'/../').DIRECTORY_SEPARATOR);
define("APP_PATH", realpath(__DIR__.'/../App/').DIRECTORY_SEPARATOR);
define("PUBLIC_PATH", realpath(__DIR__.'/../public'));
define("TEMPLATE_PATH", realpath(__DIR__.'/../templates/pages'));

require realpath(__DIR__.'/../vendor/autoload.php');

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$argvs = $_SERVER['argv'];
$action = !empty($argvs[1]) ? $argvs[1] : 'init';

require realpath(__DIR__.'/consoles/'.$action.'.php');
?>