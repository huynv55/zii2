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

require realpath(__DIR__.'/../bootstrap/loader.php');
require realpath(__DIR__.'/../bootstrap/func.php');

$app = require realpath(__DIR__.'/../bootstrap/app.php');
/**
 * @var ZiiAppFramework $app
 */
$debugExeption = $app->whoops_add_stack_frame();
try {
    $app->run();
}
catch (\Throwable $e) {
    if(env('APP_DEBUG', 0)) {
        $debugExeption->handleException($e);
    } else {
        echo $e->getMessage();
        // TODO catch default exception
    }
}
?>