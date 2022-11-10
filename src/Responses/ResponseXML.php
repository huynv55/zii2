<?php
namespace App\Responses;

use App\Resources\ResourceInterface;

class ResponseXML extends ResponseAbstract
{
    public function __construct()
    {
        parent::__construct();
        $this->headers['content-type'] = 'text/xml';
    }
}

?>