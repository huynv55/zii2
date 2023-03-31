<?php
namespace App\Requests;

use initializeLoader;

abstract class AbstractRequest implements initializeLoader
{
    protected array $headers;

    public function initialize()
    {
        if(!is_cli()) {
            $headers = [];
            foreach (getallheaders() as $header => $value) {
                $headers[strtolower($header)] = $value;
            }
            $this->headers = $headers;
        } else 
        {
            $this->headers = [];
        }
        
    }

    public function header(string $name, string $default = null)
    {
        return $this->headers[strtolower($name)] ?? $default;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function input(string $name, mixed $default = null)
    {
        return $_REQUEST[$name] ?? $default;
    }

    public function isMethod(string $method): bool
    {
        return (strtolower($method) == strtolower($_SERVER['REQUEST_METHOD']));
    }
}

?>