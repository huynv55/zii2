<?php
namespace App\Responses;

class RedirectResponse extends AbstractResponse
{
    public function initialize()
    {
        parent::initialize();
    }

    public function redirect($url)
    {
        $this->statusCode = 301;
        $this->headers['Location'] = $url;
        $this->send();
    }
}
?>