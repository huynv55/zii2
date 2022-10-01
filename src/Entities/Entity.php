<?php
namespace App\Entities;

abstract class Entity implements EntityInterface
{
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
}
?>