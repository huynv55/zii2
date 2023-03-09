<?php
namespace App\Responses;

use App\Resources\ResourceInterface;
use JsonSerializable;

class ResponseJSON extends ResponseAbstract
{
    public function __construct()
    {
        parent::__construct();
        $this->headers['content-type'] = 'application/json';
    }

    public function json(array|JsonSerializable $data)
    {
        return $this->setBody(json_encode($data));
    }
}

?>