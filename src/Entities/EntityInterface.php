<?php
namespace App\Entities;

use App\Requests\RequestInterface;

interface EntityInterface
{
    public function primaryKey();

    public function toArray() : array;

    public function fromData(array $data);

    public function patchRequestData(RequestInterface $request);
}
?>