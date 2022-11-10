<?php
namespace App\Middlewares;

class AppMiddleware extends MiddlewareAbstract
{
    public function __construct()
    {
        
    }

    public function handle()
    {
        return MiddlewareAbstract::NEXT;
    }
}
?>