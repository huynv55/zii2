<?php
namespace App\Entities;
use App\Requests\RequestInterface;

abstract class MongoEntityAbstract implements MongoEntityInterface
{
    protected array $_accessible = [];

    public function toArray(): array
    {
        $data = [];
        foreach($this->_accessible as $key => $field)
        {
            $data[$field] = $this->{$field};
        }
        return $data;
    }

    public function fromData(array $data)
    {
        foreach($this->_accessible as $key => $field)
        {
            if(isset($data[$field]))
            {
                $this->{$field} = $data[$field];
            }
        }
        return $this;
    }

    public function patchRequestData(RequestInterface $request)
    {
        $data = $request->getPostData();
        return $this->fromData($data);
    }

    public function bsonSerialize()
    {
        return $this->toArray();
    }

    public function bsonUnserialize(array $data)
    {
        return $this->fromData($data);
    }
}
?>