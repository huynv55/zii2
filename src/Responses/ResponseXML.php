<?php
namespace App\Responses;

use App\Resources\ResourceInterface;

class ResponseXML extends Response
{
    public function __construct()
    {
        parent::__construct();
        $this->headers['content-type'] = 'text/xml';
    }
}

?>