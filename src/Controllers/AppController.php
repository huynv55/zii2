<?php
namespace App\Controllers;
use App\Application;
use Monolog\Logger;

class AppController extends Controller
{

    public function getLog() : Logger
    {
        return $this->app->log();
    }

    public function settings() : array
    {
        return $this->app->getConfig();
    }
}
?>