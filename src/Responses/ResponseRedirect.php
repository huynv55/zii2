<?php
namespace App\Responses;

class ResponseRedirect extends Response
{
    public function to(string $to) : ResponseRedirect
    {
        $this->headers['Location'] = $to;
        $this->statusCode = 301;
        return $this;
    }
}

?>