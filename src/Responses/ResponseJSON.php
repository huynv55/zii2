<?php
namespace App\Responses;

use App\Resources\ResourceInterface;

class ResponseJSON extends Response
{
    public function __construct()
    {
        parent::__construct();
        $this->headers['content-type'] = 'application/json';
    }

    public function send(): string
    {
        $content = parent::send();
        $content = $this->body->toArray();
        return json_encode($content);
    }
}

?>