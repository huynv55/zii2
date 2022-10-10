<?php
namespace App\Entities;
use App\Requests\RequestInterface;

interface MongoEntityInterface extends \MongoDB\BSON\Persistable 
{
    public function toArray() : array;

    public function fromData(array $data);

    public function patchRequestData(RequestInterface $request);
}
?>