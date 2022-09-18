<?php
namespace App\Controllers;
use App\Application;

abstract class Controller implements ControllerInterface
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}

?>