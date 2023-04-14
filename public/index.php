<?php
/**
 * Zii - A PHP Framework For Web Artisans
 */
require realpath(__DIR__.'/../bootstrap/constant.php');

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
        // TODO catch default exception
        logger()->error($e->getMessage());
        logger()->error(PHP_EOL. $e->getTraceAsString());
        echo $e->getMessage();
    }
}
?>