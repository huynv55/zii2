<?php
namespace App\Middlewares;


abstract class MiddlewareAbstract implements MiddlewareInterface
{
    const NEXT = 1;
    const STOP = 0;

    public function next()
    {
        return self::NEXT;
    }

    public function stop()
    {
        return self::STOP;
    }
}
?>