<?php
namespace App\Entities;
use App\Requests\RequestInterface;
abstract class EntityAbstract implements EntityInterface
{
    public array $_accessible = [];

    public function __set($name, $value)
    {
        $method = camelCase('set_'.$name);
        if(method_exists($this, $method))
        {
            $this->{$method}($value);
        } 
        else {
            $this->{$name} = $value;
        }
    }

    public function __get($name)
    {
        $method = camelCase('get_'.$name);
        if(method_exists($this, $method))
        {
            return $this->{$method}();
        } else
        {
            return $this->{$name};
        }
    }

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
}
?>