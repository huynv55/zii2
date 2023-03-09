<?php
namespace App\Controllers;
use Monolog\Logger;

class AppController extends ControllerAbstract
{

    public function getLog() : Logger
    {
        return app()->log();
    }

    public function settings() : array
    {
        return app()->getConfig();
    }
}
?>