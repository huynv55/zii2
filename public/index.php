<?php
/**
 * Zii - A PHP Framework For Web Artisans
 */

define("ROOT_PATH", realpath(__DIR__.'/../').DIRECTORY_SEPARATOR);
define("APP_PATH", realpath(__DIR__.'/../App/').DIRECTORY_SEPARATOR);
define("PUBLIC_PATH", realpath(__DIR__.'/../public'));
define("TEMPLATE_PATH", realpath(__DIR__.'/../templates/pages'));

require realpath(__DIR__.'/../vendor/autoload.php');

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

require realpath(__DIR__.'/../bootstrap/func.php');
require realpath(__DIR__.'/../bootstrap/loader.php');

try {
/**
 * run application
 */
(require realpath(__DIR__.'/../bootstrap/app.php'))->run();
}
catch (\Exception $e) {
    echo $e->getMessage();
}
?>