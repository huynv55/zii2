<?php
namespace App\Middlewares;

class CorsMiddleware extends MiddlewareAbstract
{
    public function __construct()
    {
        
    }

    public function handle()
    {
        app()->setResponseHeader('Access-Control-Allow-Origin', '*');
        app()->setResponseHeader('Access-Control-Allow-Methods', '*');
        app()->setResponseHeader('Access-Control-Allow-Headers', '*');
        app()->setResponseHeader('Access-Control-Max-Age', 86400);
        return $this->next();
    }
}
?>