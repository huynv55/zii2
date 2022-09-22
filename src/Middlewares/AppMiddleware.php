<?php
namespace App\Middlewares;

class AppMiddleware extends Middleware
{
    public function __construct()
    {
        
    }

    public function handle()
    {
        return Middleware::NEXT;
    }
}
?>