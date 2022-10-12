<?php
namespace App\Middlewares;

interface MiddlewareInterface
{
    public function next();

    public function stop();
}

?>