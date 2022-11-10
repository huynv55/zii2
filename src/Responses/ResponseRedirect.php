<?php
namespace App\Responses;

class ResponseRedirect extends ResponseAbstract
{
    public function to(string $to) : ResponseRedirect
    {
        $this->headers['Location'] = $to;
        $this->statusCode = 301;
        return $this;
    }
}

?>